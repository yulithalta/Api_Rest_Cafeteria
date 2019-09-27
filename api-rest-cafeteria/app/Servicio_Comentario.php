<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio_Comentario extends Model
{
    public $table = "servicios_comentarios";
    const CREATED_AT = "fecha_servicio";
    const UPDATED_AT = null;
    protected $fillable = [
        "comentario_servicio", "id_cliente", "calificacion_servicio", "fecha_servicio"
    ];
    public function usuario()
    {
        return $this->belongsTo('App\Usuario', 'id_cliente');
    }
}
