<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pedido extends Model
{
    // Solo necesitas esto para que Laravel te deje guardar los datos:
    protected $fillable = [
        'user_id',
        'nombre_plato',
        'observaciones',
        'total',
        'estado'
    ];
}
