<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $fillable = ['nombreRuta', 'origen', 'destino'];
    public $timestamps = false;
}
