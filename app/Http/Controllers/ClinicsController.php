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
    public function collectBuilderIndex(array $filters) : array
    {
        $date = $filters['date'] ?? $this->defaultFilters()['date'];
        $condition = ['`cid`=`clinics`.`id`'];
        if ($date)
        {
            $startDate = date('Y-m', strtotime($date)) . '-01';
            $endDate = date("Y-m-t", strtotime($startDate)); // последний день месяца
            $condition[] = "`start` BETWEEN '$startDate' AND '$endDate 23:59:59'";
        }

        $condition = implode(' AND ', $condition);
        return [
            'builder' => DB::table('clinics')->select(DB::raw("*, (SELECT SUM(`cost` * `count`) FROM `works` WHERE {$condition}) as `salary`")),
            'filters' => $filters,
        ];
    }

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

    public function invoice(Request $request) : Response
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'date' => 'required|date',
        ]);
        $data = Clinic::findOrFail($validated['id']);

        $condition = ["`cid`={$validated['id']}"];

        $startDate = date('Y-m', strtotime($validated['date'])) . '-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // последний день месяца
        $condition[] = "`start` BETWEEN '$startDate' AND '$endDate 23:59:59'";
        $where = implode(' AND ', $condition);

        $items = DB::table('works')->select(DB::raw("`start`, `patient`, (SELECT `name` FROM `work_types` WHERE `id`=`wtid`) as `name`, `cost`, `count`, (`count`*`cost`) as `salary`"))->whereRaw($where)->get();

        return Inertia::render('Clinics/Accounting', [
            'id'        => $validated['id'],
            'date'      => $startDate,
            'name'      => $data->name,
            'items'     => $items,
            'url'       => URL::current(),
            'prev_url'  => URL::previous(),
        ]);
    }

    public function invoiceGet(int $id, int $date) : Response
    {
        $data = Clinic::findOrFail($id);

        $condition = ["`cid`={$id}"];

        [$y, $md] = mb_str_split($date, 4);
        [$m, $d] = mb_str_split($md, 2);

        $startDate = $y.'-'.$m.'-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // последний день месяца
        $condition[] = "`start` BETWEEN '$startDate' AND '$endDate 23:59:59'";
        $where = implode(' AND ', $condition);

        $items = DB::table('works')->select(DB::raw("`start`, `patient`, (SELECT `name` FROM `work_types` WHERE `id`=`wtid`) as `name`, `cost`, `count`, (`count`*`cost`) as `salary`"))->whereRaw($where)->get();

        return Inertia::render('Clinics/Accounting', ['prev_url' => URL::previous(), 'url' => URL::current(), 'url_test' => URL::full(), 'items' => $items, 'name' => $data->name, 'id' => $id, 'date' => $startDate]);
    }
}












































