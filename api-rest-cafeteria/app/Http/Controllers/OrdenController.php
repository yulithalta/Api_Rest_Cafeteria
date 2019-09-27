<?php

namespace App\Http\Controllers;

use App\Orden;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    public function index(){
        $orden = Orden::all()->load('usuario');
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "orden" => $orden
        ]);
    }

    public function show($id){
        $orden = Orden::find($id);
        if(is_object($orden)){
            //si esta tabla tiene una llave foranea vamos a realizar load
            $orden = $orden->load('usuario');
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "orden" => $orden
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Lo siento :( no se encuentra la orden que estas buscando"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function store(Request $request){
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        if(!empty($parametros)){
            $validacion = \Validator::make($parametros,[
                "pago_total" => "required",
                "id_cliente" => "required",
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $orden = new Orden();
                $orden->pago_total = $parametros["pago_total"];
                $orden->id_cliente = $parametros["id_cliente"];
                $orden->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "orden" => $orden
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
        $orden = Orden::where("id", $id)->first();
        if(!empty($alimento)){
            $orden->delete();
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "orden" => $orden
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "El alimento no se encontro"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function GetOrdenesPorUsuario($id){
        $ordenes = Orden::where("id_cliente", $id)->with('usuario')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "ordenes" => $ordenes
        ]);
    }
}
