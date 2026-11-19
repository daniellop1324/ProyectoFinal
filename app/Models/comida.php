<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comida extends Model
{
    protected $fillable = ['nombre', 'precio', 'categoria'];
}