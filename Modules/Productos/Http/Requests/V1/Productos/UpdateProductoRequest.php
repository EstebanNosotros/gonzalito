<?php

namespace App\Http\Requests\V1\Productos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
