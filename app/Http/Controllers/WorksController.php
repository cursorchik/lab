<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

use App\Models\{Clinic, Mechanic, Work, WorkType};

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;

class WorksController extends Controller
{
    protected function defaultFilters(): array
    {
        return [
            'mechanics' => [
                'selected' => 0,
                'items' => [],
            ],
            'clinics' => [
                'selected' => 0,
                'items' => [],
            ],
            'date' => date('Y-m-d'),
            'patient' => '',
        ];
    }

    public function index(Request $request): Response
    {
        $work_builder = Work::with(['workType', 'clinic', 'mechanic']);

        $date = null;
        $patient = null;
        $mid = 0;
        $cid = 0;

        $filters = $request->all()['filters'] ?? [];

        $date = $filters['date'] ?? null;
        if ($date) {
            $startDate = date('Y-m', strtotime($date)) . '-01';
            $endDate = date("Y-m-t", strtotime($startDate)); // последний день месяца
            $work_builder->whereBetween('start', [$startDate, $endDate . ' 23:59:59']);
        }

        $patient = ($filters['patient'] ?? null);
        if ($patient) $work_builder->whereLike('patient', '%' . $patient . '%');

        $mid = (int)($filters['mid'] ?? 0);
        if ($mid) $work_builder->where('mid', $mid);

        $cid = (int)($filters['cid'] ?? 0);
        if ($cid > 0) $work_builder->where('cid', $cid);



        return Inertia::render('Works/Browse', [
            'prev_url' => URL::previous(),
            'items' => $work_builder->get(),
            'default_filters' => $this->defaultFilters(),
            'filters' => [
                'date' => $date,
                'patient' => $patient,
                'mechanics' => [
                    'selected' => $mid,
                    'items' => Mechanic::all()->map(fn($m) => ['value' => $m->id, 'label' => $m->name]),
                ],
                'clinics' => [
                    'selected' => $cid,
                    'items' => Clinic::all(),
                ],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Works/Create', [
            'prev_url' => URL::previous(),
            'clinics' => Clinic::all(),
            'mechanics' => Mechanic::all(),
            'works_types' => WorkType::all()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start' => 'date',
            'end' => 'date',
            'count' => 'integer|min:0',
            'patient' => 'string|max:512',
            'cid' => 'integer|min:0',
            'mid' => 'integer|min:0',
            'wtid' => 'integer|min:0',
            'comment' => 'string|max:512|nullable',
        ]);

        $data = $request->all();
//        dd($data);
        if (!Clinic::find((int)$data['cid'], 'id')) return back()->with('error', 'Неверный ID клиники!')->withInput();
        if (!Mechanic::find((int)$data['mid'], 'id')) return back()->with('error', 'Неверный ID техника!')->withInput();
        if (!WorkType::find((int)$data['wtid'], 'id')) return back()->with('error', 'Неверный ID типа работы!')->withInput();

        $validated['state'] = Work::STATE_NOT_START;

        $validated['cost'] = 0;

        Work::create($validated);

        return to_route('works.index');
    }

    public function edit(string $id): Response
    {
        Work::findOrFail($id);
        return Inertia::render('Works/Update', [
            'prev_url' => URL::previous(),
            'data' => [
                'item' => Work::whereId($id)->first(),
                'states' => [
                    Work::STATE_NOT_START => 'Не начато',
                    Work::STATE_IN_PROCCESS => 'В процессе',
                    Work::STATE_COMPLETED => 'Завершено',
                    Work::STATE_SENT => 'Отправлено',
                ],
                'clinics' => Clinic::all(),
                'mechanics' => Mechanic::all(),
                'works_types' => WorkType::all()
            ]
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        Work::findOrFail($id);

        $validated = $request->validate([
            'start' => 'date|required',
            'end' => 'date|required',
            'count' => 'integer|min:0|required',
            'state' => 'integer|min:0|max:3|required',
            'patient' => 'string|max:512|required',
            'cid' => 'integer|min:0|required',
            'mid' => 'integer|min:0|required',
            'wtid' => 'integer|min:0|required',
            'comment' => 'string|max:512|nullable',
        ]);

        if (!Clinic::find($validated['cid'], 'id')) return back()->with('error', 'Неверный ID клиники!')->withInput();
        if (!Mechanic::find($validated['mid'], 'id')) return back()->with('error', 'Неверный ID техника!')->withInput();
        if (!$wt = WorkType::find($validated['wtid'], ['id', 'cost'])) return back()->with('error', 'Неверный ID типа работы!')->withInput();

        if ($validated['state'] == Work::STATE_COMPLETED) $validated['cost'] = $wt->cost;

//        dd($validated);

        Work::whereId($id)->update($validated);

        return to_route('works.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        Work::findOrFail($id);
        Work::destroy($id);

        return to_route('works.index');
    }
}
