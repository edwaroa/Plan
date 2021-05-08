<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Factor;
use App\Aspecto;
use App\Proyecto;
use App\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AspectoController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->rol->nombre == "Decano"){
            $aspectos = Aspecto::all();
        }else {
            $aspectos = Aspecto::where('estado', 'Activado')->get();
        }
        return view('aspectos.index', compact('aspectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $caracteristicas = Caracteristica::all(['id', 'nombre', 'codigo']);

            return view('aspectos.create', compact('caracteristicas'));
        }else {
            return redirect()->action([AspectoController::class, 'index']);
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
            'codigo' => 'required|unique:aspectos,codigo|string|max:5',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_caracteristica' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail)  use($request){
                    $aspectos = Aspecto::where('id_caracteristica', $request['id_caracteristica'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $aspectos->count(); $i++){
                        $peso_total -= $aspectos[$i]->peso;
                    }

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        DB::table('aspectos')->insert([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_caracteristica' => $data['id_caracteristica'],
            'peso' => $data['peso'],
            'progreso' => 0,
        ]);

        return redirect()->action([AspectoController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aspecto  $aspecto
     * @return \Illuminate\Http\Response
     */
    public function show(Aspecto $aspecto)
    {
        return view('aspectos.show', compact('aspecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aspecto  $aspecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Aspecto $aspecto)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $caracteristicas = Caracteristica::all(['id', 'nombre', 'codigo']);
            $aspectos = Aspecto::where('id_caracteristica', $aspecto->id_caracteristica)->where('estado', 'Activado')->get();

            $total_aspectos = $aspectos->count();
            $peso_total = 100;

            for($i = 0; $i < $total_aspectos; $i++){
                $peso_total -= $aspectos[$i]->peso;
            }

            return view('aspectos.edit', compact('caracteristicas', 'aspecto', 'peso_total'));
        }else {
            return redirect()->action([AspectoController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aspecto  $aspecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aspecto $aspecto)
    {
        $data = $request->validate([
            'codigo' => 'required|max:5',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_caracteristica' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($aspecto, $request) {
                    $aspectos = Aspecto::where('id_caracteristica', $request['id_caracteristica'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $aspectos->count(); $i++){
                        $peso_total -= $aspectos[$i]->peso;
                    }

                    $total_editar = $peso_total + $aspecto->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        if($aspecto->id_caracteristica == $data['id_caracteristica']) {
            $aspecto = Aspecto::findOrFail($aspecto->id);
            $aspecto->codigo = $data['codigo'];
            $aspecto->nombre = $data['nombre'];
            $aspecto->descripcion = $data['descripcion'];
            $aspecto->id_caracteristica = $data['id_caracteristica'];
            $aspecto->peso = $data['peso'];

            $aspecto->save();
        }else {
            $caracteristica1 = Caracteristica::find($aspecto->id_caracteristica);
            $caracteristica2 = Caracteristica::find($data['id_caracteristica']);

            $aspecto = Aspecto::findOrFail($aspecto->id);
            $aspecto->codigo = $data['codigo'];
            $aspecto->nombre = $data['nombre'];
            $aspecto->descripcion = $data['descripcion'];
            $aspecto->id_caracteristica = $data['id_caracteristica'];
            $aspecto->peso = $data['peso'];

            $aspecto->save();

            $this->progresos($caracteristica1);
            $this->progresos($caracteristica2);
        }

        return redirect()->action([AspectoController::class, 'index']);
    }

    public function estado(Request $request, Aspecto $aspecto)
    {
        $aspectos = Aspecto::where('id_caracteristica', $aspecto->id_caracteristica)->where('estado', 'Activado')->get();

        $peso_total = 100;

        for($i = 0; $i < $aspectos->count(); $i++){
            $peso_total -= $aspectos[$i]->peso;
        }

        $aspecto = Aspecto::findOrFail($aspecto->id);

        if($aspecto->estado=='Desactivado'){
            //Leer el nuevo estado
            if($aspecto->peso <= $peso_total){
                $aspecto->estado='Activado';
                $aspecto->save();

                $caracteristica = Caracteristica::find($aspecto->id_caracteristica);

                $this->progresos($caracteristica);

                return redirect()->route('aspectos.index')->with('status_estado', 'si')->with('tipo', 'Activado');
            }else {
                return redirect()->route('aspectos.index')->with('status_estado', 'no');
            }
        }
        else{
            $aspecto->estado='Desactivado';
            $aspecto->save();

            $caracteristica = Caracteristica::find($aspecto->id_caracteristica);

            $this->progresos($caracteristica);

            return redirect()->route('aspectos.index')->with('status_estado', 'si')->with('tipo', 'Desactivado');
        }
    }

    public function peso(Request $request){
        $aspecto = Aspecto::where('id_caracteristica', $request['id_caracteristica'])->where('estado', 'Activado')->get();
        $peso_total = 100;

        for($i = 0; $i < $aspecto->count(); $i++){
            $peso_total -= $aspecto[$i]->peso;
        }

        $caracteristica = Caracteristica::findOrFail($request['id_caracteristica']);
        return response()->json([
            'peso_total' => $peso_total,
            'caracteristica' => $caracteristica->nombre
        ], 200);
    }

    public function progresos(Caracteristica $caracteristica) {
        // Calculamos el progreso del aspecto

        $aspectos=Aspecto::where('id_caracteristica', $caracteristica->id)->where('estado','Activado')->get();

        $cont_progreso = 0;

        foreach($aspectos as $aspecto){
            $cont_progreso += (($aspecto->progreso * $aspecto->peso) / 100);
        }

        $caracteristica->progreso = $cont_progreso;
        $caracteristica->save();

        // Factores

        $factor = Factor::findOrFail($caracteristica->id_factor);
        $caracteristicas = Caracteristica::where('id_factor', $factor->id)->where('estado', 'Activado')->get();

        $fac_progreso = 0;

        foreach($caracteristicas as $caracteristica) {
            $fac_progreso += (($caracteristica->progreso * $caracteristica->peso) / 100);
        }

        $factor->progreso = $fac_progreso;
        $factor->save();

        // Proyectos

        $proyecto = Proyecto::findOrFail($factor->id_proyecto);
        $factores = Factor::where('id_proyecto', $proyecto->id)->where('estado', 'Activado')->get();

        $pro_progreso = 0;

        foreach($factores as $factor) {
            $pro_progreso += (($factor->progreso * $factor->peso) / 100);
        }

        $proyecto->progreso = $pro_progreso;
        $proyecto->save();

        // Plan

        $plan = Plan::findOrFail($proyecto->id_plan);
        $proyectos = Proyecto::where('id_plan', $plan->id)->where('estado', 'Activado')->get();

        $plan_progreso = 0;

        foreach($proyectos as $proyecto) {
            $plan_progreso += (($proyecto->progreso * $proyecto->peso) / 100);
        }

        $plan->progreso = $plan_progreso;
        $plan->save();


        return $caracteristica;
    }
}
