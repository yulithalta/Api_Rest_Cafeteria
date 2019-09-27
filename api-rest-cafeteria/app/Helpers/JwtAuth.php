<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Usuario;
use Illuminate\Http\Request;

class JwtAuth{
    public $llave;

    public function __construct()
    {
        $this->llave = "BelithBlue";
    }

    public function inicio($login, $password, $gettoken=null)
    {
        $usuario = Usuario::where("login", $login)->where("password", $password)->first();
        if(is_object($usuario)){
            $token = array(
                "id" => $usuario->id,
                "nombre_usuario" => $usuario->nombre_usuario,
                "apellidos" => $usuario->apellidos,
                "correo" => $usuario->correo,
                "imagen" => $usuario->imagen,
                "rol" => $usuario->rol,
                "fhi" => time(),
                "exp" => time() + (12*60*60)
            );
            $jwt = JWT::encode($token, $this->llave, "HS256");
            $decodificado = JWT::decode($jwt, $this->llave, ["HS256"]);
            if(is_null($gettoken)){
                $datos = $jwt;
            }
            else{
                $datos = $decodificado;
            }
        }
        else{
            $datos = array(
                "codigo" => 200,
                 "status" => "error",
                 "mensaje" => "No se pudo iniciar sesion"
            );
        }
       return $datos;
    }

    public function revisa_token($jwt, $get_identidad=false){

        $auth = false;
        try{
            $jwt = str_replace('"', '', $jwt);
            $decodificado = JWT::decode($jwt, $this->llave, ['HS256']);
        }
        catch (\UnexpectedValueException $e){
            $auth = false;
        }
        catch (\DomainException $e){
            $auth = false;
        }
        if (!empty($decodificado) && is_object($decodificado) && isset($decodificado->id)){
            $auth = true;
        }
        else{
            $auth = false;
        }
        if ($get_identidad){
            return $decodificado;
        }
        return $auth;
    }

    public function get_identidad(Request $request){
        $token = $request->header("Authorization", null );
        $usuario = $this->revisa_token($token, true);
        return $usuario;
    }
}