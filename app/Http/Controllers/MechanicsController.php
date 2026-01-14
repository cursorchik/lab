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

class MechanicsController extends Controller
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
        $condition = ['`mid`=`mechanics`.`id`'];
        if ($date)
        {
            $startDate = date('Y-m', strtotime($date)) . '-01';
            $endDate = date("Y-m-t", strtotime($startDate)); // последний день месяца
            $condition[] = "`start` BETWEEN '$startDate' AND '$endDate 23:59:59'";
        }

        $condition = implode(' AND ', $condition);
        return [
            'builder' => DB::table('mechanics')->select(DB::raw("*, (SELECT SUM(`cost` * `count`) FROM `works` WHERE {$condition}) as `salary`")),
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

    public function store(MechanicsRequest $request) : RedirectResponse
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

    public function update(MechanicsRequest $request, string $id) : RedirectResponse
    {
        Mechanic::findOrFail($id);
        $validated = $request->validate();

        Mechanic::whereId($id)->update($validated);

        return to_route('mechanics.index');
    }

    public function destroy(string $id) : RedirectResponse
    {
        Mechanic::findOrFail($id);
        Mechanic::destroy($id);

        return to_route('mechanics.index');
    }

    public function invoice(Request $request) : Response
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'date' => 'required|date',
        ]);
        $data = Mechanic::findOrFail($validated['id']);

        $condition = ["`mid`={$validated['id']}"];

        $startDate = date('Y-m', strtotime($validated['date'])) . '-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // последний день месяца
        $condition[] = "`start` BETWEEN '$startDate' AND '$endDate 23:59:59'";
        $where = implode(' AND ', $condition);

        $items = DB::table('works')->select(DB::raw("`patient`, (SELECT `name` FROM `work_types` WHERE `id`=`wtid`) as `name`, `cost`, `count`, (`count`*`cost`) as `salary`"))->whereRaw($where)->get();

        return Inertia::render('Mechanics/Accounting', [
            'id'        => $validated['id'],
            'name'      => $data->name,
            'date'      => $startDate,
            'items'     => $items,
        ]);
    }

    public function invoiceGet(int $id, int $date) : Response
    {
        $data = Mechanic::findOrFail($id);

        $condition = ["`mid`={$id}"];

        [$y, $md] = mb_str_split($date, 4);
        [$m, $d] = mb_str_split($md, 2);

        $startDate = $y.'-'.$m.'-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // последний день месяца
        $condition[] = "`start` BETWEEN '$startDate' AND '$endDate 23:59:59'";
        $where = implode(' AND ', $condition);

        $items = DB::table('works')->select(DB::raw("`patient`, (SELECT `name` FROM `work_types` WHERE `id`=`wtid`) as `name`, `cost`, `count`, (`count`*`cost`) as `salary`"))->whereRaw($where)->get();

        return Inertia::render('Mechanics/Accounting', [
            'id' => $id,
            'name' => $data->name,
            'date' => $startDate,
            'items' => $items,
        ]);
    }
}
