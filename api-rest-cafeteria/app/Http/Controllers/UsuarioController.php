<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JwtAuth;
class UsuarioController extends Controller
{
    public function __construct()
    {
        //$this->middleware("api.auth:Administrador, Empleado, Cliente", ["except"=>["sesion", "index", "store"]]);
        //$this->middleware("api.auth:Administrador, Cliente", ["except"=>["sesion", "index","show", "update", "SubirImagen", "GetImagen", "destroy", "store"]]);
        //$this->middleware("api.auth:Administrador", ["except"=>["sesion", "store","show", "update", "SubirImagen", "GetImagen", "destroy"]]);
    }

    public function index(){
        $usuarios = Usuario::all();
        return response()->json([
            "codigo" => 200,
            "status" => "exito",
            "usuarios" => $usuarios
        ]);
    }

    public function show($id){
        $usuario = Usuario::find($id);
        if(is_object($usuario)){
            $datos = array(
                "codigo" => "200",
                "status" => "exito",
                "usuario" => $usuario
            );
        }
        else{
            $datos = array(
                "codigo" => "400",
                "status" => "error",
                "mensaje" => "Lo siento :( no se encuentra el usuario que estas buscando"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function store(Request $request){
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        if(!empty($parametros)){
            $validacion = \Validator::make($parametros,[
                "nombre_usuario" => "required",
                "apellidos" => "required",
                "login" => "required|unique:usuarios",
                "password" => "required",
                "correo" => "required|email",
                "rol" => "required"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => 200,
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                $usuario = new Usuario();
                $password = hash("sha256", $parametros["password"]);
                $usuario->nombre_usuario = $parametros["nombre_usuario"];
                $usuario->apellidos = $parametros["apellidos"];
                $usuario->login = $parametros["login"];
                $usuario->password = $password;
                $usuario->correo = $parametros["correo"];
                $usuario->imagen = $parametros["imagen"];
                $usuario->rol = $parametros["rol"];
                $usuario->save();
                $datos = array(
                    "codigo" => 200,
                    "status" => "exito",
                    "usuario" => $usuario
                );
            }
        }
        else{
            $datos = array(
                "codigo" => 200,
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
                "nombre_usuario" => ["required",Rule::unique("usuarios")->ignore($id)],
                "apellidos" => "required",
                "login" => "required|unique:usuarios",
                "password" => "required",
                "correo" => "required|email",
                "rol" => "required"
            ]);
            if($validacion->fails()){
                $datos = array(
                    "codigo" => 200,
                    "status" => "error",
                    "errores" => $validacion->errors()
                );
            }
            else{
                unset($parametros["id"]);
                Usuario::where("id",$id)->update($parametros);
                $datos = array(
                    "codigo" => 200,
                    "status" => "exito",
                    "usuario" => $parametros
                );
            }
        }
        else{
            $datos = array(
                "codigo" => 400,
                "status" => "error",
                "mensaje" => "usuario no enviado"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function destroy($id){
        $usuario = Usuario::where("id", $id)->first();
        if(!empty($usuario)){
            $usuario->delete();
            $datos = array(
                "codigo" => 200,
                "status" => "exito",
                "usuario" => $usuario
            );
        }
        else{
            $datos = array(
                "codigo" => 200,
                "status" => "error",
                "mensaje" => "El usuario no se encontro"
            );
        }
        return response()->json($datos, $datos["codigo"]);
    }

    public function SubirImagen(Request $request){
        $imagen = $request->file( "file0");
        $validacion = \Validator::make($request->all(),[
            "file0" => "required|image|mimes:jpg,png,jpeg,gif"
        ]);
        if ( !$imagen || $validacion->fails() ){
            $datos = array(
                "codigo" => 200,
                "estatus" => 'error',
                "mensaje" => 'No se subio la imagen'
            );
        }else
        {
            $nombre_imagen = time().$imagen->getClientOriginalName();
            \Storage::disk("usuario_imagen")->put( $nombre_imagen, \File::get($imagen) );
            $datos = array(
                "codigo" => 200,
                "estatus" => 'exito',
                "imagen" => $nombre_imagen
            );
        }
        return response()->json( $datos,$datos["codigo"]);
    }
    public function GetImagen($nombre_imagen){
        $isset = \Storage::disk("usuario_imagen")->exists($nombre_imagen);
        if ($isset){
            $archivo = \Storage::disk("usuario_imagen")->get($nombre_imagen);
            return New Response($archivo, 200);
        }
        else{
            $datos = array(
                "codigo" => 200,
                "estatus" => 'error',
                "mensaje" => "La imagen no se ha cargado correctamente"
            );
        }
        return response()->json( $datos, $datos["codigo"]);
    }

    public function sesion(Request $request){
        $jwtAuth = new JwtAuth();
        $json = $request->input("json", "null");
        $parametros = json_decode($json, "true");
        $validacion = \Validator::make($parametros,[
            "login" => "required",
            "password" => "required",
        ]);
        if($validacion->fails()){
            $datos = array(
                "codigo" => 200,
                "status" => "error",
                "errores" => $validacion->errors()
            );
        }
        else{
            $password = hash( "sha256", $parametros["password"]);
            $inicio = $jwtAuth->inicio($parametros["login"], $password);
            if(!empty($parametros['gettoken'])){
                $inicio = $jwtAuth->inicio($parametros["login"], $password, true);
            }
        }
        return response()->json( $inicio, 200);
    }
}
