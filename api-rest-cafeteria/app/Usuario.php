<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;
    public $table = "usuarios";
    const CREATED_AT = "creado";
    const UPDATED_AT = null;
    protected $fillable = [
        "nombre_usuario", "apellidos", "login", "password", "correo", "imagen", "rol", "creado"
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}