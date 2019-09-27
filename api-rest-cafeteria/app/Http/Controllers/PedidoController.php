<?php

namespace App\Http\Controllers;

use App\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(){
        $pedido = Pedido::all()->load('alimento','orden');
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "pedido" => $pedido
        ]);
    }

    public function show($id){
        $pedido = Pedido::find($id);
        if(is_object($pedido)){
            $pedido = $pedido->load('alimento','orden');
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "pedido" => $pedido
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Lo siento :( no se encuentra el pedido que estas buscando"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function store(Request $request){
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        if(!empty($parametros)){
            $validacion = \Validator::make($parametros,[
                "id_alimento" => "required|numeric",
                "cantidad" => "required|numeric",
                "precio" => "required|numeric",
                "nota" => "required",
                "id_orden" => "required|numeric"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $pedido = new Pedido();
                $pedido->id_alimento = $parametros["id_alimento"];
                $pedido->cantidad = $parametros["cantidad"];
                $pedido->precio = $parametros["precio"];
                $pedido->nota = $parametros["nota"];
                $pedido->id_orden = $parametros["id_orden"];
                $pedido->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "pedido" => $pedido
                );
            }
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Datos enviados incorrectamente"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function destroy($id){
        $pedido = Pedido::where("id", $id)->first();
        if(!empty($pedido)){
            $pedido->delete();
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "pedido" => $pedido
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "El pedido no se encontro"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function GetPedidosPorOrdenes($id){
        $pedidos = Pedido::where("id_orden", $id)->with('orden')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "pedidos" => $pedidos
        ]);
    }
    public function GetPedidosPorAlimentos($id){
        $pedidos = Pedido::where("id_alimento", $id)->with('alimento')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "pedidos" => $pedidos
        ]);
    }
}
