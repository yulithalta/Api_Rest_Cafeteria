<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
    public $table = "alimentos";
    public $timestamps = FALSE; // no exista el CREATED AT Y UPDATE AT
    protected $fillable = [
      "nombre_alimento", "descripcion_alimento", "precio", "imagen", "id_categoria_alimento", "tiempo_preparacion"
    ];
    public function categoria_alimento()
    {
        return $this->belongsTo('App\Categoria_Alimento', 'id_categoria_alimento');
    }
    public function pedido()
    {
       return $this->hasMany("App\Pedido");
    }
    public function alimento_comentario()
    {
        return $this->hasMany("App\Alimento_Comentario");
    }
}
