<?php

namespace App\Http\Controllers;


use App\Plan;
use App\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $proyectos = Proyecto::all();
        }else {
            $proyectos = Proyecto::where('estado','Activado')->get();
        }

        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $planes = Plan::all(['id', 'nombre']);
            $proyectos = Proyecto::all();
            $total_proyectos = $proyectos->count();
            $peso_total = 100;

            for($i = 0; $i < $total_proyectos; $i++){
                $peso_total -= $proyectos[$i]->peso;
            }

            return view('proyectos.create', compact('planes', 'peso_total'));
        }else {
            return redirect()->action([ProyectoController::class, 'index']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'objetivo_general' => 'required | string',
            'objetivos_especificos' => 'required | string',
            'id_plan' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) {
                    $proyectos = Proyecto::all();
                    $peso_total = 100;

                    for($i = 0; $i < $proyectos->count(); $i++){
                        $peso_total -= $proyectos[$i]->peso;
                    }

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        DB::table('proyectos')->insert([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'objetivo_general' => $data['objetivo_general'],
            'objetivos_especificos' => $data['objetivos_especificos'],
            'id_plan' => $data['id_plan'],
            'peso' => $data['peso'],
            'progreso' => 0
        ]);

        return redirect()->action([ProyectoController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function show(Proyecto $proyecto)
    {
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Proyecto $proyecto)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $planes = Plan::all(['id', 'nombre']);
            $proyectos = Proyecto::all();
            $total_proyectos = $proyectos->count();
            $peso_total = 100;

            for($i = 0; $i < $total_proyectos; $i++){
                $peso_total -= $proyectos[$i]->peso;
            }

            return view('proyectos.edit', compact('proyecto', 'planes', 'peso_total'));
        }else {
            return redirect()->action([ProyectoController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'objetivo_general' => 'required | string',
            'objetivos_especificos' => 'required | string',
            'id_plan' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($proyecto) {
                    $proyectos = Proyecto::all();
                    $peso_total = 100;

                    for($i = 0; $i < $proyectos->count(); $i++){
                        $peso_total -= $proyectos[$i]->peso;
                    }

                    $total_editar = $peso_total + $proyecto->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        $proyecto = Proyecto::findOrFail($proyecto->id);
        $proyecto->nombre = $data['nombre'];
        $proyecto->descripcion = $data['descripcion'];
        $proyecto->objetivo_general = $data['objetivo_general'];
        $proyecto->objetivos_especificos = $data['objetivos_especificos'];
        $proyecto->id_plan = $data['id_plan'];
        $proyecto->peso = $data['peso'];

        $proyecto->save();

        return redirect()->action([ProyectoController::class, 'index']);
    }

    public function estado(Request $request, Proyecto $proyecto)
    {
        if($proyecto->estado=='Desactivado'){
            //Leer el nuevo estado
            $proyecto->estado='Activado';
            $proyecto->save();
        }
        else{
            $proyecto->estado='Desactivado';
            $proyecto->save();
        }
        return redirect()->action([ProyectoController::class, 'index']);
    }
}
