<?php

namespace App\Http\Requests\V1\Banners;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }
}
