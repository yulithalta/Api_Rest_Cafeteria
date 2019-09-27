<?php

namespace App\Http\Controllers;

use App\Dato_Cafeteria;
use Illuminate\Http\Request;

class DatoCafeteriaController extends Controller
{
    public function index(){
        $dato_cafeteria = Dato_Cafeteria::all();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "dato_cafeteria" => $dato_cafeteria
        ]);
    }

    public function show($id){
        $dato_cafeteria = Dato_Cafeteria::find($id);
        if(is_object($dato_cafeteria)){
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "dato_cafeteria" => $dato_cafeteria
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Lo siento :( no se encuentra el dato que estas buscando"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function store(Request $request){
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        if(!empty($parametros)){
            $validacion = \Validator::make($parametros,[
                "nombre" => "required",
                "correo" => "required",
                "logo" => "required"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $dato_cafeteria = new Dato_Cafeteria();
                $dato_cafeteria->nombre = $parametros["nombre"];
                $dato_cafeteria->correo = $parametros["correo"];
                $dato_cafeteria->logo = $parametros["logo"];
                $dato_cafeteria->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "dato_cafeteria" => $dato_cafeteria
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
                "nombre" => ["required",Rule::unique("datos_cafeteria")->ignore($id)],
                "correo" => "required",
                "logo" => "required"
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
                Dato_Cafeteria::where("id",$id)->update($parametros);
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "dato_cafeteria" => $parametros
                );
            }
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Dato no enviado"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }


}
