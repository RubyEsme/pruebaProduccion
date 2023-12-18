<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiales extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'codigo_mp',
        'descripcion_1',
    ];

}
