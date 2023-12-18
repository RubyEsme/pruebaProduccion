<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitesHistorico extends Model
{
    use HasFactory;

    protected $table = 'limites_historico';

    protected $fillable = [
        'codigo_mp',
        'descripcion_1',
        'mes',
        'año',
        'limite',
        'entregado',
        'status',
        'porcentaje_uso',
        'kg_diferencia',
        'observaciones',
    ];

}
