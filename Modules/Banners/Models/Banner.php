<?php

namespace Modules\Banners\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Banners\Database\Factories\BannerFactory;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre'
        ,'imagen_desktop'
        ,'imagen_mobile'
        ,'link'
        ,'referencia'
        ,'mostrar'
        ,'destacar'
        ,'tipo'
    ];
    
    protected static function newFactory()
    {
        return BannerFactory::new();
    }
}
