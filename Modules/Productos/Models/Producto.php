<?php

namespace Modules\Productos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Productos\Database\Factories\ProductoFactory;
use Modules\Categorias\Models\Categoria;
// use Modules\Productos\Models\ProductoCuota;
use Carbon\Carbon;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre'
        ,'nombre_web'
        ,'descripcion'
        ,'codigo'
        ,'precio'
        ,'marca'
        ,'categoria_id'
        ,'tags'
        ,'imagen_principal'
        ,'productos_relacionados'
        ,'referencia'
        ,'mostrar'
        ,'destacar'
        ,'en_stock'
        ,'ultima_sincronizacion'
        ,'cuotas'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /*public function cuotas()
    {
        return $this->hasMany(ProductoCuota::class);
    }*/ /// cuando cuotas tenia su propia tabla
    
    protected static function newFactory()
    {
        return ProductoFactory::new();
    }
}
