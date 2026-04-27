<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicsRequest;
use App\Interfaces\IWork;
use App\Models\Clinic;
use App\Models\ClinicWorkLock;
use App\Models\Work;
use App\Traits\Filters;

use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Query\Builder;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClinicsController extends Controller
{
	protected function collectBuilderIndex() :  Builder
	{
		return DB::table('clinics')
			->select('clinics.*')
			->selectSub(function ($sub) {
				$sub->from('works')
					->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
					->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
					->whereRaw('`works`.`cid` = `clinics`.`id`')
					->where('works.state', IWork::STATE_COMPLETED)
					->whereNotExists(function ($query) {
						$query->select(DB::raw(1))
							->from('clinic_work_locks')
							->whereColumn('clinic_work_locks.work_id', 'works.id')
							->whereColumn('clinic_work_locks.clinic_id', 'works.cid');
					})
					->selectRaw('SUM(`work_types`.`cost` * `work_work_type`.`count`)');
			}, 'salary');
	}

    public function indexData(Request $request) : JsonResponse
    {
        return response()->json([
            'items' => $this->collectBuilderIndex()->get()
        ]);
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Clinics/Browse', [
            'items' => $this->collectBuilderIndex()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Clinics/Create', ['prev_url' => URL::previous()]);
    }

    public function store(ClinicsRequest $request) : RedirectResponse
    {
        $validated = $request->validated();

        Clinic::create($validated);

        return to_route('clinics.index');
    }

    public function edit(string $id): Response
    {
        Clinic::findOrFail($id);
        return Inertia::render('Clinics/Update', ['prev_url' => URL::previous(), 'item' => Clinic::whereId($id)->first()]);
    }

    public function update(ClinicsRequest $request, string $id) : RedirectResponse
    {
        Clinic::findOrFail($id);
        $validated = $request->validated();

        Clinic::whereId($id)->update($validated);

        return to_route('clinics.index');
    }

    public function destroy(string $id) : RedirectResponse
    {
		return back()
			->with('error', 'Для удаления обратитесь к администратору')
			->withInput();

        Clinic::findOrFail($id);
        Clinic::destroy($id);

        return to_route('clinics.index');
    }

	public function getItemsInvoice(string $id) : Collection
	{
		return DB::table('works')
			->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
			->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
			->where('works.cid', $id)
			->where('works.state', IWork::STATE_COMPLETED)
			->whereNotExists(function ($query) use ($id)
			{
				$query->select(DB::raw(1))
					->from('clinic_work_locks')
					->whereColumn('clinic_work_locks.work_id', 'works.id')
					->where('clinic_work_locks.clinic_id', $id);
			})
			->select(
				'works.id',
				'works.start',
				'works.patient',
				'work_types.name',
				'work_types.cost',
				'work_work_type.count',
				DB::raw('`work_types`.`cost` * `work_work_type`.`count` as salary')
			)
			->get()
			->groupBy('id');
	}

	public function invoice(Request $request) : Response
	{
		$validated = $request->validate([
			'id' => 'required|integer'
		]);

		$items = $this->getItemsInvoice($validated['id']);

		return Inertia::render('Clinics/Accounting', [
			'id'       => $validated['id'],
			'url'      => URL::current(),
			'name'     => Clinic::findOrFail($validated['id'])->name,
			'items'    => $items,
			'prev_url' => URL::previous(),
		]);
	}

	public function invoiceGet(int $id) : Response
	{
		$items = $this->getItemsInvoice($id);

		return Inertia::render('Clinics/Accounting', [
			'id'       => $id,
			'url'      => URL::current(),
			'name'     => Clinic::findOrFail($id)->name,
			'items'    => $items,
			'prev_url' => URL::previous(),
		]);
	}

	public function lockWorks(Request $request) : RedirectResponse
	{
		$validated = $request->validate([
			'clinic_id' => 'required|exists:clinics,id',
			'work_ids'  => 'required|array',
			'work_ids.*' => 'exists:works,id',
		]);

		foreach ($validated['work_ids'] as $workId)
		{
			$work = Work::find($workId);
			if ($work && $work->cid == $validated['clinic_id'])
			{
				ClinicWorkLock::firstOrCreate([
					'work_id' => $workId,
					'clinic_id' => $validated['clinic_id'],
				], ['locked_at' => now()]);
			}
		}

		return back();
	}
}












































