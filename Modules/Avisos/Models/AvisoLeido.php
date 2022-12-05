<?php

namespace Modules\Avisos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Avisos\Database\Factories\AvisoFactory;
use App\Models\User;

class AvisoLeido extends Model
{
    use HasFactory;
    protected $table = 'avisos_leidos';
    protected $fillable = [
        'aviso_id'
        ,'user_id'
    ];

    public function aviso()
    {
        return $this->belongsTo(Aviso::class);
    }
}
