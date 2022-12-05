<?php

namespace Modules\Avisos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Avisos\Database\Factories\AvisoFactory;

class Aviso extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo'
        ,'cuerpo'
    ];
    
    protected static function newFactory()
    {
        return AvisoFactory::new();
    }

    public function leidos()
    {
        return $this->hasMany(AvisoLeido::class);
    }
}
