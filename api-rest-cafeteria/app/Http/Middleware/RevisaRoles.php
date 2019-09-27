<?php

namespace App\Http\Middleware;

use App\Helpers\JwtAuth;
use Closure;

class RevisaRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $Roles = explode(",", $roles);
        $jwtauth = new JwtAuth();
        $usuario = $jwtauth->get_identidad($request);
        foreach ($Roles as $rol){
            if ($usuario->rol == $rol) {
                return $next($request);
            }
        }
        $datos = array(
            "codigo" => 400,
            "estatus" => 'error',
            "mensaje" => "No tienes permiso"
        );
        return response()->json( $datos,$datos["codigo"]);
    }
}
