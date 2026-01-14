<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorksRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'start'     => 'required|date',
            'end'       => 'required|date',
            'count'     => 'required|integer|min:0',
            'state'     => 'required|integer|min:0|max:3',
            'patient'   => 'required|string|max:512',
            'cid'       => 'required|integer|min:0',
            'mid'       => 'required|integer|min:0',
            'wtid'      => 'required|integer|min:0',
            'comment'   => 'string|max:512|nullable',
        ];
    }

    public function messages() : array
    {
        return [
            'start.required'     => 'Дата начала обязательна',
            'start.date'         => 'Дата начала должна быть датой',
            'end.required'       => 'Дата окончания обязательна',
            'end.date'           => 'Дата окончания должна быть датой',
            'count.required'     => 'Количество обязательно',
            'count.integer'      => 'Количество должно быть целым числом',
            'count.min'          => 'Количество не может быть меньше :min',
            'state.required'     => 'Статус обязателен',
            'state.integer'      => 'Статус должен быть целым числом',
            'state.min'          => 'Статус не может быть меньше :min',
            'state.max'          => 'Статус не может быть больше :max',
            'patient.required'   => 'Пациент обязателен',
            'patient.string'     => 'Пациент должен быть строкой',
            'patient.max'        => 'Пациент не может быть длиннее :max символов',
            'cid.required'       => 'Клиника обязательна',
            'cid.integer'        => 'Клиника должна быть целым числом',
            'cid.min'            => 'Клиника не может быть меньше :min',
            'mid.required'       => 'Механик обязателен',
            'mid.integer'        => 'Механик должен быть целым числом',
            'mid.min'            => 'Механик не может быть меньше :min',
            'wtid.required'      => 'Тип работы обязателен',
            'wtid.integer'       => 'Тип работы должен быть целым числом',
            'wtid.min'           => 'Тип работы не может быть меньше :min',
            'comment.string'     => 'Комментарий должен быть строкой',
            'comment.max'        => 'Комментарий не может быть длиннее :max символов',
        ];
    }
}
