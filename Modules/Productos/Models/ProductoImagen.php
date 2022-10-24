<?php

namespace Modules\Productos\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Productos\Models\Producto;

class ProductoImagen extends Model
{
    protected $table = 'producto_imagenes';
    protected $fillable = [
        'imagen'
        ,'producto_id'
        ,'mostrar'
        ,'destacar'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
