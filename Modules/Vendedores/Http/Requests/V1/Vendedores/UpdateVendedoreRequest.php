<?php

namespace App\Http\Requests\V1\Vendedores;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendedoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
