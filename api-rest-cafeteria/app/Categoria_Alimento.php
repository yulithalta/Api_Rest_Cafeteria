<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria_Alimento extends Model
{
    public $table = "categorias_alimentos";
    public $timestamps = FALSE; // no exista el CREATED AT Y UPDATE AT
    protected $fillable = [
        "nombre_categoria", "descripcion_categoria", "hora_inicial","hora_final"
    ];
    public function alimento()
    {
        return $this->hasMany("App\Alimento");
    }
}
