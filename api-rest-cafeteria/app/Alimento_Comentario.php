<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alimento_Comentario extends Model
{
    public $table = "alimentos_comentarios";
    const CREATED_AT = "fecha_alimento";
    const UPDATED_AT = null;
    protected $fillable = [
        "comentario_alimento", "id_cliente", "calificacion_alimento", "id_alimento", "fecha_alimento"
    ];
    public function usuario()
    {
        return $this->belongsTo('App\Usuario', 'id_cliente');
    }
    public function alimento()
    {
        return $this->belongsTo('App\Alimento', 'id_alimento');
    }
}
