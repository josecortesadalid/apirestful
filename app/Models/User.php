<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
 
    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';
    protected $dates = ['deleted_at'];

    protected $fillable = [ // Tanto en verified como en admin vamos a hacer uso de unas constantes que nos permitan verificar ambos estados (las creo arriba)
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = strtolower($valor); // lo pone en minúscula
    }
    public function getNameAttribute($valor)
    {
        return ucwords($valor); // primera mayúscula en cada una de las palabras
    }
    public function setEmailAttribute($valor)
    {
        $this->attributes['email'] = strtolower($valor);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    protected function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    protected function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function generarVerificationToken()   // Es estático debido a que no requerimos de una instancia de usuario para poder generar dicho token de verificación 
    {
        return  2552999; //Str::random(40); // Formado por un total de 40 caracteres que serán formados de manera aleatoria
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
