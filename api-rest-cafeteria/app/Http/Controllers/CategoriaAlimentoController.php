<?php

namespace App\Http\Controllers;

use App\Categoria_Alimento;
use Illuminate\Http\Request;

class CategoriaAlimentoController extends Controller
{
    public function index(){
        $categorias_alimentos = Categoria_Alimento::all();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "alimentos" => $categorias_alimentos
        ]);
    }

    public function show($id){
        $categoria_alimento = Categoria_Alimento::find($id);
        if(is_object($categoria_alimento)){
                $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "alimento" => $categoria_alimento
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
                "nombre_categoria" => "required",
                "descripcion_categoria" => "required",
                "hora_inicial" => "required",
                "hora_final" => "required|after:hora_inicial"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $categoria_alimento = new Categoria_Alimento();
                $categoria_alimento->nombre_categoria = $parametros["nombre_categoria"];
                $categoria_alimento->descripcion_categoria = $parametros["descripcion_categoria"];
                $categoria_alimento->hora_inicial = $parametros["hora_inicial"];
                $categoria_alimento->hora_final = $parametros["hora_final"];
                $categoria_alimento->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "alimento" => $categoria_alimento
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

    public function update($id, Request $request){
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        if(!empty($parametros)){
            $validacion = \Validator::make($parametros,[
                "nombre_categoria" => ["required",Rule::unique("categorias_alimentos")->ignore($id)],
                "descripcion_categoria" => "required|numeric",
                "hora_inicial" => "required",
                "hora_final" => "required|after:hora_inicial"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                unset($parametros["id"]);
                Categoria_Alimento::where("id",$id)->update($parametros);
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "alimento" => $parametros
                );
            }
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Categoria no enviada"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }
    public function destroy($id){
        $categoria_alimento = Categoria_Alimento::where("id", $id)->first();
        if(!empty($categoria_alimento)){
            $categoria_alimento->delete();
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "alimento" => $categoria_alimento
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "La categoria no se encontro"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }
}
