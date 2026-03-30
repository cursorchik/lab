<?php

namespace App\Http\Controllers;

use App\Http\Requests\MechanicsRequest;
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

	#[ArrayShape([
		'builder' => Builder::class,
		'filters' => [
			'date' => 'string',
		],
	])]
	public function collectBuilderIndex(array $filters): array
	{
		$date = $filters['date'] ?? $this->defaultFilters()['date'];
		$startDate = $this->getStartDate($date);
		$endDate = $this->getEndDate($date);

		$query = DB::table('mechanics')
			->select('mechanics.*')
			->selectSub(function ($sub) use ($startDate, $endDate) {
				$sub->from('works')
					->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
					->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
					->whereRaw('`works`.`mid` = `mechanics`.`id`')
					->whereBetween('works.start', [$startDate, $endDate])
					->selectRaw('SUM(`work_types`.`cost` * `work_work_type`.`count`)');
			}, 'salary');

		return [
			'builder' => $query,
			'filters' => $filters,
		];
	}

	public function indexData(Request $request): JsonResponse
	{
		$data = $this->collectBuilderIndex($request->all()['filters'] ?? []);

		return response()->json([
			'items' => $data['builder']->get()
		]);
	}

	public function index(Request $request): Response
	{
		$data = $this->collectBuilderIndex($this->getFilters($request));

		return Inertia::render('Mechanics/Browse', [
			'items' => $data['builder']->get(),
			'default_filters' => $this->defaultFilters(),
			'filters' => [
				'date' => $data['filters']['date'] ?? null,
			],
		]);
	}

	public function create(): Response
	{
		return Inertia::render('Mechanics/Create', ['prev_url' => URL::previous()]);
	}

	public function store(MechanicsRequest $request): RedirectResponse
	{
		$validated = $request->validated();

		Mechanic::create($validated);

		return to_route('mechanics.index');
	}

	public function edit(string $id): Response
	{
		Mechanic::findOrFail($id);
		return Inertia::render('Mechanics/Update', ['prev_url' => URL::previous(), 'item' => Mechanic::whereId($id)->first()]);
	}

	public function update(MechanicsRequest $request, string $id): RedirectResponse
	{
		Mechanic::findOrFail($id);
		$validated = $request->validated();

		Mechanic::whereId($id)->update($validated);

		return to_route('mechanics.index');
	}

	public function destroy(string $id): RedirectResponse
	{
		Mechanic::findOrFail($id);
		Mechanic::destroy($id);

		return to_route('mechanics.index');
	}

	public function invoice(Request $request): Response
	{
		$validated = $request->validate([
			'id'   => 'required|integer',
			'date' => 'required|date',
		]);

		$mechanic = Mechanic::findOrFail($validated['id']);

		$startDate = $this->getStartDate($validated['date']);
		$endDate   = $this->getEndDate($validated['date']);

		$items = DB::table('works')
			->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
			->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
			->where('works.mid', $validated['id'])
			->whereBetween('works.start', [$startDate, $endDate])
			->select(
				'works.patient',
				'works.start',
				'work_types.name',
				'work_types.cost',
				'work_work_type.count',
				DB::raw('`work_types`.`cost` * `work_work_type`.`count` as salary')
			)
			->get();

		return Inertia::render('Mechanics/Accounting', [
			'id'    => $validated['id'],
			'name'  => $mechanic->name,
			'date'  => $startDate,
			'items' => $items,
		]);
	}

	public function invoiceGet(int $id, int $date): Response
	{
		$mechanic = Mechanic::findOrFail($id);

		$y = substr($date, 0, 4);
		$m = substr($date, 4, 2);
		$startDate = $y . '-' . $m . '-01';
		$endDate   = date('Y-m-t 23:59:59', strtotime($startDate));

		$items = DB::table('works')
			->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
			->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
			->where('works.mid', $id)
			->whereBetween('works.start', [$startDate, $endDate])
			->select(
				'works.patient',
				'works.start',
				'work_types.name',
				'work_types.cost',
				'work_work_type.count',
				DB::raw('`work_types`.`cost` * `work_work_type`.`count` as salary')
			)
			->get();

		return Inertia::render('Mechanics/Accounting', [
			'id'    => $id,
			'name'  => $mechanic->name,
			'date'  => $startDate,
			'items' => $items,
		]);
	}
}
