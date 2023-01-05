<?php

namespace App\Http\Requests\V1\Catalogo_auditorias;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatalogo_auditoriaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
