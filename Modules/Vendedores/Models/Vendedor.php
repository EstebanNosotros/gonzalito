<?php

namespace Modules\Vendedores\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Vendedores\Database\Factories\VendedorFactory;

class Vendedor extends Model
{
    use HasFactory;
    // protected $fillable = ['nombre'];
    
    protected static function newFactory()
    {
        return VendedorFactory::new();
    }
}
