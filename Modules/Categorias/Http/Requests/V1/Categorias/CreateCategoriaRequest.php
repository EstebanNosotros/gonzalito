<?php

namespace App\Http\Requests\V1\Categorias;

use Illuminate\Foundation\Http\FormRequest;;

class CreateCategoriaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }
}
