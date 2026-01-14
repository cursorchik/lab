<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorksTypesRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'name' => 'required|max:255',
            'cost' => 'required|integer|min:1'
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'Укажите ваш название',
            'name.max' => 'Название должно содержать не более :max символов',
            'cost.required' => 'Укажите цену',
            'cost.integer' => 'Цена должна быть цеклым числом',
            'cost.min' => 'Цена должна быть не меньше :min',
        ];
    }
}
