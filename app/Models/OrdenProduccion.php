<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenProduccion extends Model
{
    use HasFactory;

    protected $table = 'ordenes_produccion';

    protected $fillable = [
        'usuario',
        'noOrden',
        'fecha',
        'codigo_mp',
        'descripcion_1',
        'requerido',
        'um',
        'entregado',
        'pendiente',
        'devuelto',
        'status',
        'motivo_devolucion',
    ];
}
