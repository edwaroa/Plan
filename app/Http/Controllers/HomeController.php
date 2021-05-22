<?php

namespace App\Http\Controllers;

use App\User;
use App\Actividad;
use App\Aspecto;
use App\Caracteristica;
use App\Factor;
use App\Indicador;
use App\Plan;
use App\Proyecto;
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
        $planes = Plan::where('estado', 'Activado')->count();
        $proyectos = Proyecto::where('estado', 'Activado')->count();
        $factores = Factor::where('estado', 'Activado')->count();
        $caracteristicas = Caracteristica::where('estado', 'Activado')->count();
        $aspectos = Aspecto::where('estado', 'Activado')->count();
        $indicadores = Indicador::where('estado', 'Activado')->count();
        $actividades = Actividad::where('estado', 'Activado')->count();

        $widget = [
            'users' => $users,
            'planes' => $planes,
            'proyectos' => $proyectos,
            'factores' => $factores,
            'caracteristicas' => $caracteristicas,
            'aspectos' => $aspectos,
            'indicadores' => $indicadores,
            'actividades' => $actividades
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
