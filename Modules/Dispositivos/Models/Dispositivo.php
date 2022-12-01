<?php

namespace Modules\Dispositivos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dispositivos\Database\Factories\DispositivoFactory;

class Dispositivo extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id'
        ,'hash'
        ,'user_agent'
    ];
    
    protected static function newFactory()
    {
        return DispositivoFactory::new();
    }
}
