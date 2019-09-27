<?php

namespace App\Http\Controllers;

use App\Servicio_Comentario;
use Illuminate\Http\Request;

class ServicioComentarioController extends Controller
{
    public function index(){
        $servicios_comentarios = Servicio_Comentario::all()->load('usuario');
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "servicios_comentarios" => $servicios_comentarios
        ]);
    }

    public function show($id){
        $servicio_comentario = Servicio_Comentario::find($id);
        if(is_object($servicio_comentario)){
            //si esta tabla tiene una llave foranea vamos a realizar load
            $servicio_comentario = $servicio_comentario->load('usuario');
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "servicio_comentario" => $servicio_comentario
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Lo siento :( no se encuentra el comentario que estas buscando"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function store(Request $request){
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        if(!empty($parametros)){
            $validacion = \Validator::make($parametros,[
                "comentario_servicio" => "required|unique:servicios_comentarios",
                "id_cliente" => "required|unique:usuarios",
                "calificacion_servicio" => "required|numeric",
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $servicio_comentario = new Servicio_Comentario();
                $servicio_comentario->comentario_servicio = $parametros["comentario_servicio"];
                $servicio_comentario->id_cliente = $parametros["id_cliente"];
                $servicio_comentario->calificacion_servicio = $parametros["calificacion_servicio"];
                $servicio_comentario->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "servicios_comentarios" => $servicio_comentario
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

    public function GetServiciosComentariosPorUsuario($id){
        $servicios_comentarios = Servicio_Comentario::where("id_cliente", $id)->with('usuario')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "servicios_comentarios" => $servicios_comentarios
        ]);
    }
}
