<?php

namespace Modules\Categorias\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Categorias\Database\Factories\CategoriaFactory;
use Modules\Productos\Models\Producto;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'nombre_web',
        'imagen',
        'icono',
        'referencia',
        'mostrar',
        'destacar',
    ];

    private function productos()
    {
        return $this->hasMany(Producto::class);
    }
    
    protected static function newFactory()
    {
        return CategoriaFactory::new();
    }
}
