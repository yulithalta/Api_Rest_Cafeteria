<?php

namespace App\Http\Controllers;

use App\Alimento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;

class AlimentoController extends Controller
{
    public function index(){
        $alimentos = Alimento::all()->load('categoria_alimento'); // :: accede al metodo del modelo.... load para cargar llaves foraneas completas
        return response()->json([
           "codigo" => 200, // 200 correcto 300 advertencia 400 error
            "status" => "exito",
            "alimentos" => $alimentos
        ]);
    }

    public function show($id){
        $alimento = Alimento::find($id);
        if(is_object($alimento)){
            //si esta tabla tiene una llave foranea vamos a realizar load
            $alimento = $alimento->load('categoria_alimento');
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "alimento" => $alimento
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
               "nombre_alimento" => "required|unique:alimentos",
                "precio" => "required|numeric",
                "id_categoria_alimento" => "required|numeric",
                "tiempo_preparacion" => "required"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => "400",
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $alimento = new Alimento();
                $alimento->nombre_alimento = $parametros["nombre_alimento"];
                if(isset($parametros["descripcion_alimento"])){
                    $alimento->descripcion_alimento = $parametros["descripcion_alimento"];
                }
                $alimento->precio = $parametros["precio"];
                $alimento->imagen = $parametros["imagen"];
                $alimento->id_categoria_alimento = $parametros["id_categoria_alimento"];
                $alimento->tiempo_preparacion = $parametros["tiempo_preparacion"];
                $alimento->save();
                $datos = array(
                    "codigo" => "200",
                    "status" => "exito",
                    "alimento" => $alimento
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
                "nombre_alimento" => ["required",Rule::unique("alimentos")->ignore($id)],
                "precio" => "required|numeric",
                "id_categoria_alimento" => "required|numeric",
                "tiempo_preparacion" => "required"
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
                Alimento::where("id",$id)->update($parametros);
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
                "mensaje" => "Alimento no enviado"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }
    public function destroy($id){
        $alimento = Alimento::where("id", $id)->first();
        if(!empty($alimento)){
            $alimento->delete();
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "alimento" => $alimento
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

    public function GetAlimentosPorCategoriaAlimento($id){
        $alimentos = Alimento::where("id_categoria_alimento", $id)->with('categoria_alimento')->get();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "alimentos" => $alimentos
        ]);
    }

    public function SubirImagen(Request $request){
        $imagen = $request->file( "file0");
        $validacion = \Validator::make($request->all(),[
            "file0" => "required|image|mimes:jpg,png,jpeg,gif"
        ]);
        if ( !$imagen || $validacion->fails() ){
            $datos = array(
                "codigo" => 400,
                "estatus" => 'error',
                "mensaje" => 'No se subio la imagen'
            );
        }else
            {
            $nombre_imagen = time().$imagen->getClientOriginalName();
            \Storage::disk("alimento_imagen")->put( $nombre_imagen, \File::get($imagen) );
            $datos = array(
                "codigo" => 200,
                "estatus" => 'exito',
                "imagen" => $nombre_imagen
            );
        }
        return response()->json( $datos,$datos["codigo"]);
    }

    public function GetImagen($nombre_imagen){
        $isset = \Storage::disk("alimento_imagen")->exists($nombre_imagen);
        if ($isset){
            $archivo = \Storage::disk("alimento_imagen")->get($nombre_imagen);
            return New Response($archivo, 200);
        }
        else{
            $datos = array(
                "codigo" => 400,
                "estatus" => 'error',
                "mensaje" => "La imagen no se ha cargado correctamente"
            );
        }
        return response()->json( $datos, $datos["codigo"]);
    }
}
