<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_documento', 'documento', 'nombre', 'apellido', 'email', 'password', 'id_rol', 'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        if (is_null($this->apellido)) {
            return "{$this->nombre}";
        }

        return "{$this->nombre} {$this->apellido}";
    }

    public function index(){


        return view('usuarios.index');
    }


    //Obtener el Rol via FK
    public function rol(){
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function actividades() {
        return $this->belongsToMany(Actividad::class)->withPivot('id');
    }
}
