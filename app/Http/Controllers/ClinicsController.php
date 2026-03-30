<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicsRequest;
use App\Models\Clinic;
use App\Traits\Filters;

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
    use Filters;

    protected function defaultFilters(): array
    {
        return [
            'date' => date('Y-m-d'),
        ];
    }

    #[ArrayShape([
        'builder' => Builder::class,
        'filters' => [
            'date' => 'string',
        ],
    ])]
	protected function collectBuilderIndex(array $filters): array
	{
		$date = $filters['date'] ?? $this->defaultFilters()['date'];
		$startDate = $this->getStartDate($date);
		$endDate = $this->getEndDate($date);

		$query = DB::table('clinics')
			->select('clinics.*')
			->selectSub(function ($sub) use ($startDate, $endDate) {
				$sub->from('works')
					->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
					->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
					->whereRaw('`works`.`cid` = `clinics`.`id`')
					->whereBetween('works.start', [$startDate, $endDate])
					->selectRaw('SUM(`work_types`.`cost` * `work_work_type`.`count`)');
			}, 'salary');

		return [
			'builder' => $query,
			'filters' => $filters,
		];
	}

	protected function getStartDate(string $date): string { return date('Y-m', strtotime($date)) . '-01'; }
	protected function getEndDate(string $date): string { return date('Y-m-t 23:59:59', strtotime($this->getStartDate($date))); }

    public function indexData(Request $request) : JsonResponse
    {
        $data = $this->collectBuilderIndex($request->all()['filters'] ?? []);

        return response()->json([
            'items' => $data['builder']->get()
        ]);
    }

    public function index(Request $request): Response
    {
        $data = $this->collectBuilderIndex($this->getFilters($request));

        return Inertia::render('Clinics/Browse', [
            'items' => $data['builder']->get(),
            'default_filters' => $this->defaultFilters(),
            'filters' => [
                'date' => $data['filters']['date'] ?? null,
            ],
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
        Clinic::findOrFail($id);
        Clinic::destroy($id);

        return to_route('clinics.index');
    }

	public function invoice(Request $request): Response
	{
		$validated = $request->validate([
			'id'   => 'required|integer',
			'date' => 'required|date',
		]);

		$clinic = Clinic::findOrFail($validated['id']);

		$startDate = $this->getStartDate($validated['date']);
		$endDate   = $this->getEndDate($validated['date']);

		$items = DB::table('works')
			->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
			->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
			->where('works.cid', $validated['id'])
			->whereBetween('works.start', [$startDate, $endDate])
			->select(
				'works.start',
				'works.patient',
				'work_types.name',
				'work_types.cost',
				'work_work_type.count',
				DB::raw('`work_types`.`cost` * `work_work_type`.`count` as salary')
			)
			->get();

		return Inertia::render('Clinics/Accounting', [
			'id'       => $validated['id'],
			'date'     => $startDate,
			'name'     => $clinic->name,
			'items'    => $items,
			'url'      => URL::current(),
			'prev_url' => URL::previous(),
		]);
	}

	public function invoiceGet(int $id, int $date): Response
	{
		$clinic = Clinic::findOrFail($id);

		$y = substr($date, 0, 4);
		$m = substr($date, 4, 2);
		$startDate = $y . '-' . $m . '-01';
		$endDate   = date('Y-m-t 23:59:59', strtotime($startDate));

		$items = DB::table('works')
			->join('work_work_type', 'works.id', '=', 'work_work_type.work_id')
			->join('work_types', 'work_work_type.work_type_id', '=', 'work_types.id')
			->where('works.cid', $id)
			->whereBetween('works.start', [$startDate, $endDate])
			->select(
				'works.start',
				'works.patient',
				'work_types.name',
				'work_types.cost',
				'work_work_type.count',
				DB::raw('`work_types`.`cost` * `work_work_type`.`count` as salary')
			)
			->get();

		return Inertia::render('Clinics/Accounting', [
			'prev_url' => URL::previous(),
			'url'      => URL::current(),
			'items'    => $items,
			'name'     => $clinic->name,
			'id'       => $id,
			'date'     => $startDate,
		]);
	}
}












































