<?php

namespace App\Http\Controllers;

use App\User;
use App\Actividad;
use App\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usuarios=User::where('estado','Activado');
        $users = $usuarios->count();
        $widget = [
            'users' => $users
        ];

        return view('home', compact('widget'));
    }

    //Funcion para listar los manuales
    public function manuales(){
        return view('manuales');
    }
    //Funcion para descargar el manual
    public function descargar($nombre){
        //Buscamos el maual usando el nombre y lo descargamos
        return response()->download(storage_path("app/public/manuales/{$nombre}"));
    }
}
