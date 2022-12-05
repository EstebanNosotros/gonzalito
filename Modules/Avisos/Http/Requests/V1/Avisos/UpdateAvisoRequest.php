<?php

namespace App\Http\Requests\V1\Avisos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvisoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
