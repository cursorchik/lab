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
}
