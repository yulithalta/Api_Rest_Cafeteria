<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public $table = "pedidos";
    public $timestamps = FALSE;
    protected $fillable = [
        "id_alimento", "cantidad", "precio",  "nota", "id_orden"
    ];
    public function alimento()
    {
        return $this->belongsTo('App\Alimento', 'id_alimento');
    }
    public function orden()
    {
        return $this->belongsTo('App\Orden', 'id_orden');
    }
}
