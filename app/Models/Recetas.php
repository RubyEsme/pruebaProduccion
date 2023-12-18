<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recetas extends Model
{
    use HasFactory;

    protected $table = 'recetas';

    protected $fillable = [
        'sku',
        'formato',
        'modelo',
        'tipo',
        'codigo_mp',
        'descripcion',
        'descripcion_1',
        'cantidad',
        'um',
        'planta',
        'linea',
        'rodillo_digital',
        'observaciones',
        'idreceta',
        'proveedor',
        'ficha',
    ];

}

