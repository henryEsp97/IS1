<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarioviaje extends Model
{
    protected $fillable = ['dniusuario', 'idViaje', 'estado'];
    public $timestamps = false;
}
