<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    public $table = "ordenes";
    const CREATED_AT = "fecha_orden";
    const UPDATED_AT = null;
    protected $fillable = [
        "fecha_orden", "pago_total", "id_cliente"
    ];
    public function usuario()
    {
        return $this->belongsTo('App\Usuario', 'id_cliente');
    }
    public function pedido()
    {
        return $this->hasMany("App\Pedido");
    }
}
