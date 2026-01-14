<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorksTypesRequest;
use App\Models\WorkType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class WorksTypesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('WorksTypes/Browse', ['prev_url' => URL::previous(), 'items' => WorkType::all()]);
    }

    public function create(): Response
    {
        return Inertia::render('WorksTypes/Create', ['prev_url' => URL::previous()]);
    }

    public function preview(): Response
    {
        return Inertia::render('WorksTypes/Preview', ['prev_url' => URL::previous()]);
    }

    /**
     * @throws ValidationException
     */
    public function import(Request $request) : RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'form'          => 'required|array|min:1',
            'form.*.name'   => 'required|string|max:255',
            'form.*.cost'   => 'required|integer|min:1',
        ], [
            'form.required'         => 'Необходимо добавить хотя бы один элемент',
            'form.array'            => 'Данные должны быть массивом',
            'form.min'              => 'Необходимо добавить хотя бы один элемент',
            'form.*.name.required'  => 'Название работы обязательно для заполнения',
            'form.*.name.max'       => 'Название работы не должно превышать :max символов',
            'form.*.cost.required'  => 'Цена обязательна для заполнения',
            'form.*.cost.integer'   => 'Цена должна быть числом',
            'form.*.cost.min'       => 'Цена не может быть менее :min',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error', 'Пожалуйста, исправьте ошибки в форме')
                ->withInput(); // Это сохранит введенные данные
        }

        if (!WorkType::insert($validator->validated()['form']))
        {
            return back()
                ->with('error', 'Ошибка записи в БД')
                ->withInput(); // Это сохранит введенные данные
        };

        return to_route('works_types.index');
    }

    public function store(WorksTypesRequest $request) : RedirectResponse
    {
        $validated = $request->validated();

        WorkType::create($validated);

        return to_route('works_types.index');
    }

    public function edit(string $id): Response
    {
        WorkType::findOrFail($id);
        return Inertia::render('WorksTypes/Update', ['prev_url' => URL::previous(), 'item' => WorkType::whereId($id)->first()]);
    }

    public function update(WorksTypesRequest $request, string $id) : RedirectResponse
    {
        WorkType::findOrFail($id);
        $validated = $request->validated();

        WorkType::whereId($id)->update($validated);

        return to_route('works_types.index');
    }

    public function destroy(string $id) : RedirectResponse
    {
        WorkType::findOrFail($id);
        WorkType::destroy($id);

        return to_route('works_types.index');
    }
}
