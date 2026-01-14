<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MechanicsRequest extends FormRequest
{
    public function rules() : array
    {
        return ['name' => 'required|max:255'];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'Укажите название',
            'name.max' => 'Название должно содержать не более :max символов'
        ];
    }
}
