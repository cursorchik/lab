<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorksRequest extends FormRequest
{
    public function rules() : array
    {
        return [
			'start' 			=> 'required|date|after_or_equal:1900-01-01|before_or_equal:2100-12-31',
			'end'   			=> 'required|date|after_or_equal:1900-01-01|before_or_equal:2100-12-31',
            'state'				=> 'required|integer|min:0|max:3',
            'patient'			=> 'required|string|max:512',
            'cid'				=> 'required|integer|min:0',
            'mid'				=> 'required|integer|min:0',
			'works'     		=> 'required|array|min:1',
			'works.*.id'		=> 'required|integer|exists:work_types,id',
			'works.*.quantity'	=> 'required|integer|min:1',
            'comment'			=> 'string|max:512|nullable',
        ];
    }

//    public function messages() : array
//    {
//        return [
//            'start.required'			=> 'Дата начала обязательна',
//            'start.date'				=> 'Дата начала должна быть датой',
//            'end.required'				=> 'Дата окончания обязательна',
//            'end.date'					=> 'Дата окончания должна быть датой',
//
//            'state.required'			=> 'Статус обязателен',
//            'state.integer'				=> 'Статус должен быть целым числом',
//            'state.min'					=> 'Статус не может быть меньше :min',
//            'state.max'					=> 'Статус не может быть больше :max',
//            'patient.required'			=> 'Пациент обязателен',
//            'patient.string'			=> 'Пациент должен быть строкой',
//            'patient.max'				=> 'Пациент не может быть длиннее :max символов',
//            'cid.required'				=> 'Клиника обязательна',
//            'cid.integer'				=> 'Клиника должна быть целым числом',
//            'cid.min'					=> 'Клиника не может быть меньше :min',
//            'mid.required'				=> 'Механик обязателен',
//            'mid.integer'				=> 'Механик должен быть целым числом',
//            'mid.min'					=> 'Механик не может быть меньше :min',
//			'works.required'			=> 'Необходимо выбрать хотя бы один тип работы',
//			'works.array'				=> 'Неверный формат типов работ',
//			'works.*.id.required'		=> 'ID типа работы обязателен',
//			'works.*.id.exists'			=> 'Выбран неверный тип работы',
//			'works.*.quantity.required'	=> 'Количество обязательно',
//			'works.*.quantity.integer'	=> 'Количество должно быть целым числом',
//			'works.*.quantity.min'		=> 'Количество должно быть не менее :min',
//            'comment.string'			=> 'Комментарий должен быть строкой',
//            'comment.max'				=> 'Комментарий не может быть длиннее :max символов',
//        ];
//    }
}
