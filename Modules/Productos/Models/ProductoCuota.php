<?php

namespace Modules\Productos\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Productos\Models\Producto;

class ProductoCuota extends Model
{
    protected $fillable = [
        'cuotas'
        ,'monto'
        ,'producto_id'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
