<?php

namespace App\Http\Requests\V1\Vendedores;

use Illuminate\Foundation\Http\FormRequest;;

class CreateVendedoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
