<?php

namespace App\Http\Requests\V1\Productos;

use Illuminate\Foundation\Http\FormRequest;;

class CreateProductoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
