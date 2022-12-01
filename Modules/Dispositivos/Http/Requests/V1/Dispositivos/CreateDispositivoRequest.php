<?php

namespace App\Http\Requests\V1\Dispositivos;

use Illuminate\Foundation\Http\FormRequest;;

class CreateDispositivoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
