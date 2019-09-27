<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato_Cafeteria extends Model
{
    public $table = "datos_cafeteria";
    public $timestamps = FALSE;
    protected $fillable = [
        "nombre", "correo", "logo"
    ];
}
