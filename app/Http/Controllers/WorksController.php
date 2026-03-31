<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorksRequest;
use App\Interfaces\IWork;
use App\Traits\Filters;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\{Clinic, ClinicWorkLock, Mechanic, MechanicWorkLock, Work, WorkType};

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;

class WorksController extends Controller implements IWork
{
	use Filters;

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
			'lock_type' => 'none',
        ];
    }

	public function index(Request $request) : Response
	{
		$work_builder = Work::with(['workTypes', 'clinic', 'mechanic']);

		$date = $request->get('date');
		if ($date)
		{
			$startDate = date('Y-m', strtotime($date)) . '-01';
			$endDate = date("Y-m-t", strtotime($startDate));
			$work_builder->whereBetween('start', [$startDate, $endDate . ' 23:59:59']);
		}

		$patient = $request->get('patient');
		if ($patient) $work_builder->whereLike('patient', '%' . $patient . '%');

		$mid = (int) $request->get('mid', 0);
		if ($mid) $work_builder->where('mid', $mid);

		$cid = (int) $request->get('cid', 0);
		if ($cid > 0) $work_builder->where('cid', $cid);

		$sort = $request->get('sort', 'id');
		$direction = $request->get('direction', 'asc');
		if (in_array($sort, ['id', 'start', 'end'])) $work_builder->orderBy($sort, $direction);

		$lockType = $request->get('lock_type', '');
		if ($lockType)
		{
			switch ($lockType)
			{
				case 'clinic':
					// Заблокировано клиникой, но НЕ техником
					$work_builder->whereHas('clinicLock', function ($query)
					{
						$query->whereColumn('clinic_work_locks.clinic_id', 'works.cid');
					})
					->whereDoesntHave('mechanicLock');
					break;
				case 'mechanic':
					// Заблокировано техником, но НЕ клиникой
					$work_builder->whereHas('mechanicLock', function ($query)
					{
						$query->whereColumn('mechanic_work_locks.mechanic_id', 'works.mid');
					})
					->whereDoesntHave('clinicLock');
					break;
				case 'both':
					// Заблокировано и клиникой, и техником
					$work_builder->whereHas('clinicLock', function ($query)
					{
						$query->whereColumn('clinic_work_locks.clinic_id', 'works.cid');
					})
					->whereHas('mechanicLock', function ($query)
					{
						$query->whereColumn('mechanic_work_locks.mechanic_id', 'works.mid');
					});
					break;
				case 'any':
					// Заблокировано любой стороной
					$work_builder->where(function($query) {
						$query->whereHas('clinicLock', function ($q)
						{
							$q->whereColumn('clinic_work_locks.clinic_id', 'works.cid');
						})
						->orWhereHas('mechanicLock', function($q)
						{
							$q->whereColumn('mechanic_work_locks.mechanic_id', 'works.mid');
						});
					});
					break;
				case 'none':
					// Не заблокировано
					$work_builder->whereDoesntHave('clinicLock')->whereDoesntHave('mechanicLock');
					break;
			}
			}

		$items = $work_builder->get();

		// Загружаем блокировки для всех работ
		$workIds = $items->pluck('id');
		$clinicLocks = ClinicWorkLock::whereIn('work_id', $workIds)->get()->keyBy('work_id');
		$mechanicLocks = MechanicWorkLock::whereIn('work_id', $workIds)->get()->keyBy('work_id');

		$items->transform(function ($work) use ($clinicLocks, $mechanicLocks) {
			$work->locked_by_clinic = $clinicLocks->has($work->id);
			$work->locked_by_mechanic = $mechanicLocks->has($work->id);
			$work->locked_both = $work->locked_by_clinic && $work->locked_by_mechanic;
			return $work;
		});

		return Inertia::render('Works/Browse', [
			'prev_url' => URL::previous(),
			'items' => $items,
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
				'lock_type' => $lockType
			],
			'sort' => $sort,
			'direction' => $direction,
		]);
	}

    public function create() : Response
    {
        return Inertia::render('Works/Create', [
            'prev_url' => URL::previous(),
            'clinics' => Clinic::all(),
            'mechanics' => Mechanic::all(),
            'works_types' => WorkType::all()
        ]);
    }

	public function store(WorksRequest $request) : RedirectResponse
	{
		$validated = $request->validated();

		// Проверяем существование клиники и механика, что бы не померло потом
		if (!Clinic::find($validated['cid'])) return back()->with('error', 'Неверный ID клиники!')->withInput();
		if (!Mechanic::find($validated['mid'])) return back()->with('error', 'Неверный ID техника!')->withInput();

		$work = Work::create([
			'start'   => $validated['start'],
			'end'     => $validated['end'],
			'state'   => self::STATE_NOT_START,
			'patient' => $validated['patient'],
			'cid'     => $validated['cid'],
			'mid'     => $validated['mid'],
			'comment' => $validated['comment'] ?? null,
			'cost'    => 0,
		]);

		$pivotData = [];
		foreach ($validated['works'] as $workType) $pivotData[$workType['id']] = ['count' => $workType['quantity']];

		// Привязываем типы работ с указанием количества
		$work->workTypes()->attach($pivotData);

		return to_route('works.index');
	}

	public function edit(string $id): Response
	{
		$work = Work::with('workTypes')->findOrFail($id);

		$currentWorkTypes = $work->workTypes->map(function ($type) { return ['id' => $type->id, 'quantity' => $type->pivot->count]; })->toArray();

		return Inertia::render('Works/Update', [
			'prev_url' => URL::previous(),
			'data' => [
				'item' => $work,
				'states' => [
					self::STATE_NOT_START => 'Не начато',
					self::STATE_IN_PROCESS => 'В процессе',
					self::STATE_COMPLETED => 'Завершено',
					self::STATE_SENT => 'Отправлено',
				],
				'clinics' => Clinic::all(),
				'mechanics' => Mechanic::all(),
				'works_types' => WorkType::all(),
				'current_work_types' => $currentWorkTypes, // передаём текущие типы
			]
		]);
	}

	public function update(WorksRequest $request, string $id) : RedirectResponse
	{
		$work = Work::findOrFail($id);

		if ($work->isLockedForClinic() || $work->isLockedForMechanic()) {
			return back()->with('error', 'Эта работа заблокирована и не может быть изменена.');
		}

		$validated = $request->validated();

		if (!Clinic::find($validated['cid'])) return back()->with('error', 'Неверный ID клиники!')->withInput();
		if (!Mechanic::find($validated['mid'])) return back()->with('error', 'Неверный ID техника!')->withInput();

		$work->update([
			'start'   => $validated['start'],
			'end'     => $validated['end'],
			'state'   => $validated['state'],
			'patient' => $validated['patient'],
			'cid'     => $validated['cid'],
			'mid'     => $validated['mid'],
			'comment' => $validated['comment'] ?? null,
		]);

		$pivotData = [];
		foreach ($validated['works'] as $workType) { $pivotData[$workType['id']] = ['count' => $workType['quantity']]; }

		// Этот вызов удаляет из таблицы `work_work_type` все записи у которых `work_work_type`.`work`.`id`={$id} <--- (типо id редактируемой работы)
		$work->workTypes()->sync($pivotData);

		if ($validated['state'] == self::STATE_COMPLETED)
		{
			$totalCost = 0;

			// А этот вызов выгружает в модель Work записи из таблицы `work_work_type` у которых `work_work_type`.`work`.`id`={$id}
			$work->load('workTypes');
			foreach ($work->workTypes as $type) $totalCost += $type->cost * $type->pivot->count;
			$work->update(['cost' => $totalCost]);
		}

		return to_route('works.index');
	}

    public function destroy(string $id) : RedirectResponse
    {
        $work = Work::findOrFail($id);

		if ($work->isLockedForClinic() || $work->isLockedForMechanic()) {
			return back()->with('error', 'Эта работа заблокирована и не может быть удалена.');
		}

        Work::destroy($id);

        return to_route('works.index');
    }

	public function unlock(Request $request, string $id) : RedirectResponse
	{
		$work = Work::findOrFail($id);
		$type = $request->input('unlock_type', 'both');

		if ($type === 'clinic' || $type === 'both')
		{
			ClinicWorkLock::where('work_id', $id)->delete();
		}

		if ($type === 'mechanic' || $type === 'both')
		{
			MechanicWorkLock::where('work_id', $id)->delete();
		}

		return back()->with('success', 'Блокировка снята.');
	}
}
