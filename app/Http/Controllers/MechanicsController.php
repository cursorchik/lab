<?php

namespace App\Http\Controllers;

use App\Http\Requests\MechanicsRequest;
use App\Interfaces\IWork;
use App\Models\MechanicWorkLock;
use App\Models\Work;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

use App\Traits\Filters;
use App\Models\Mechanic;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use JetBrains\PhpStorm\ArrayShape;

class MechanicsController extends Controller
{
	use Filters;

	protected function defaultFilters(): array
	{
		return [
			'date' => date('Y-m-d'),
		];
	}

	private function getStartDate(string $date): string
	{
		return date('Y-m', strtotime($date)) . '-01';
	}

	private function getEndDate(string $date): string
	{
		$startDate = $this->getStartDate($date);
		return date('Y-m-t 23:59:59', strtotime($startDate));
	}

	public function collectBuilderIndex() : Builder
	{
		return DB::table('mechanics')
			->select('mechanics.*')
			->selectSub(function ($sub) {
				$sub->from('works')
					->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
					->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
					->whereRaw('`works`.`mid` = `mechanics`.`id`')
					->where('works.state', IWork::STATE_COMPLETED)
					->whereNotExists(function ($query) {
						$query->select(DB::raw(1))
							->from('mechanic_work_locks')
							->whereColumn('mechanic_work_locks.work_id', 'works.id')
							->whereColumn('mechanic_work_locks.mechanic_id', 'works.mid');
					})
					->selectRaw('SUM(`work_types`.`cost_mechanic` * `work_work_type`.`count`)');
			}, 'salary');
	}

	public function indexData() : JsonResponse
	{
		return response()->json([
			'items' => $this->collectBuilderIndex()->get()
		]);
	}

	public function index() : Response
	{
		return Inertia::render('Mechanics/Browse', [
			'items' => $this->collectBuilderIndex()->get(),
		]);
	}

	public function create() : Response
	{
		return Inertia::render('Mechanics/Create', ['prev_url' => URL::previous()]);
	}

	public function store(MechanicsRequest $request) : RedirectResponse
	{
		$validated = $request->validated();

		Mechanic::create($validated);

		return to_route('mechanics.index');
	}

	public function edit(string $id) : Response
	{
		Mechanic::findOrFail($id);
		return Inertia::render('Mechanics/Update', ['prev_url' => URL::previous(), 'item' => Mechanic::whereId($id)->first()]);
	}

	public function update(MechanicsRequest $request, string $id) : RedirectResponse
	{
		Mechanic::findOrFail($id);
		$validated = $request->validated();

		Mechanic::whereId($id)->update($validated);

		return to_route('mechanics.index');
	}

	public function destroy(string $id) : RedirectResponse
	{
		return back()
			->with('error', 'Для удаления обратитесь к администратору')
			->withInput();

		Mechanic::findOrFail($id);
		Mechanic::destroy($id);

		return to_route('mechanics.index');
	}

	public function getItemsInvoice(string $id) : Collection
	{
		return DB::table('works')
			->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
			->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
			->where('works.mid', $id)
			->where('works.state', IWork::STATE_COMPLETED)
			->whereNotExists(function ($query) use ($id)
			{
				$query->select(DB::raw(1))
					->from('mechanic_work_locks')
					->whereColumn('mechanic_work_locks.work_id', 'works.id')
					->where('mechanic_work_locks.mechanic_id', $id);
			})
			->select(
				'works.id',
				'works.patient',
				'works.start',
				'work_types.name',
				'work_types.cost_mechanic as cost',
				'work_work_type.count',
				DB::raw('`work_types`.`cost_mechanic` * `work_work_type`.`count` as salary')
			)
			->get()
			->groupBy('id');;
	}

	public function invoice(Request $request) : Response
	{
		$validated = $request->validate([
			'id' => 'required|integer'
		]);

		$items = $this->getItemsInvoice($validated['id']);

		return Inertia::render('Mechanics/Accounting', [
			'id'    => $validated['id'],
			'name'  => Mechanic::findOrFail($validated['id'])->name,
			'items' => $items,
		]);
	}

	public function invoiceGet(int $id) : Response
	{
		$items = $this->getItemsInvoice($id);

		return Inertia::render('Mechanics/Accounting', [
			'id'    => $id,
			'name'  => Mechanic::findOrFail($id)->name,
			'items' => $items,
		]);
	}

	public function lockWorks(Request $request) : RedirectResponse
	{
		$validated = $request->validate([
			'mechanic_id' => 'required|exists:mechanics,id',
			'work_ids'    => 'required|array',
			'work_ids.*'  => 'exists:works,id',
		]);

		foreach ($validated['work_ids'] as $workId)
		{
			$work = Work::find($workId);
			if ($work && $work->mid == $validated['mechanic_id'])
			{
				MechanicWorkLock::firstOrCreate([
					'work_id' => $workId,
					'mechanic_id' => $validated['mechanic_id'],
				], ['locked_at' => now()]);
			}
		}

		return back();
	}
}
