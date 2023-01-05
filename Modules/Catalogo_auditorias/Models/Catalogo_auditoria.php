<?php

namespace Modules\Catalogo_auditorias\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Catalogo_auditorias\Database\Factories\Catalogo_auditoriaFactory;

class Catalogo_auditoria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];
    
    protected static function newFactory()
    {
        return Catalogo_auditoriaFactory::new();
    }
}
