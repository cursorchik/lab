<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\App;

use Illuminate\Foundation\Http\FormRequest;

class ClinicsRequest extends FormRequest
{
    public function rules() : array
    {
        return [
			'name' => 'required|max:255',
//			'id' => 'required|integer',
//            'date' => 'required|date',
		];
    }
}
