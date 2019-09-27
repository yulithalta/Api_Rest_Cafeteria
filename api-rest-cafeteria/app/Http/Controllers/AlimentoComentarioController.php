<?php

namespace App\Http\Controllers;

use App\Alimento;
use App\Alimento_Comentario;
use Illuminate\Http\Request;

class AlimentoComentarioController extends Controller
{
    public function index(){
        $alimentos_comentarios = Alimento_Comentario::all()->load('alimento','usuario');
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "alimentos_comentarios" => $alimentos_comentarios
        ]);
    }

    public function show($id){
        $alimento_comentario = Alimento_Comentario::find($id);
        if(is_object($alimento_comentario)){
            //si esta tabla tiene una llave foranea vamos a realizar load
            $alimento_comentario = $alimento_comentario->load('alimento', 'usuario');
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "alimento_comentario" => $alimento_comentario
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
                "comentario_alimento" => "required|unique:alimentos_comentarios",
                "id_cliente" => "required|unique:usuarios",
                "calificacion_alimento" => "required|numeric",
                "id_alimento" => "required|unique:alimentos",
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $alimento_comentario = new Alimento_Comentario();
                $alimento_comentario->comentario_alimento = $parametros["comentario_alimento"];
                $alimento_comentario->id_cliente = $parametros["id_cliente"];
                $alimento_comentario->calificacion_alimento = $parametros["calificacion_alimento"];
                $alimento_comentario->id_alimento = $parametros["id_alimento"];
                $alimento_comentario->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "alimento_comentario" => $alimento_comentario
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

    public function GetAlimentosComentariosPorUsuario($id){
        $alimentos_comentarios = Alimento_Comentario::where("id_cliente", $id)->with('usuario')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "alimentos_comentarios" => $alimentos_comentarios
        ]);
    }
    public function GetAlimentosComentariosPorAlimento($id){
        $alimentos_comentarios = Alimento_Comentario::where("id_alimento", $id)->with('alimento')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "alimentos_comentarios" => $alimentos_comentarios
        ]);
    }
}
