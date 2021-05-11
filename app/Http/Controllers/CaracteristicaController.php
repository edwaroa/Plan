<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Factor;
use App\Proyecto;
use App\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CaracteristicaController extends Controller
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
            $caracteristicas = Caracteristica::all();
        }else {
            $caracteristicas = Caracteristica::where('estado', 'Activado')->get();
        }

        return view('caracteristicas.index', compact('caracteristicas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $factores = Factor::all(['id', 'codigo', 'nombre']);
            $caracteristicas = Caracteristica::all();

            $total_caracteristicas = $caracteristicas->count();
            $peso_total = 100;

            for($i = 0; $i < $total_caracteristicas; $i++){
                $peso_total -= $caracteristicas[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

            return view('caracteristicas.create', compact('factores', 'peso_total'));
        }else {
            return redirect()->action([CaracteristicaController::class, 'index']);
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
            'codigo' => 'required|unique:caracteristicas,codigo|string|max:3',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_factor' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail)  use($request){
                    $caracteristicas = Caracteristica::where('id_factor', $request['id_factor'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $caracteristicas->count(); $i++){
                        $peso_total -= $caracteristicas[$i]->peso;
                    }

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        DB::table('caracteristicas')->insert([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_factor' => $data['id_factor'],
            'peso' => $data['peso'],
            'progreso' => 0
        ]);

        return redirect()->action([CaracteristicaController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Caracteristica  $caracteristica
     * @return \Illuminate\Http\Response
     */
    public function show(Caracteristica $caracteristica)
    {
        return view('caracteristicas.show', compact('caracteristica'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Caracteristica  $caracteristica
     * @return \Illuminate\Http\Response
     */
    public function edit(Caracteristica $caracteristica)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $factores = Factor::all(['id', 'nombre', 'codigo']);
            $caracteristicas = Caracteristica::where('id_factor', $caracteristica->id_factor)->where('estado', 'Activado')->get();

            $total_caracteristicas = $caracteristicas->count();
            $peso_total = 100;

            for($i = 0; $i < $total_caracteristicas; $i++){
                $peso_total -= $caracteristicas[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

            return view('caracteristicas.edit', compact('factores', 'caracteristica', 'peso_total'));
        }else {
            return redirect()->action([CaracteristicaController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Caracteristica  $caracteristica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caracteristica $caracteristica)
    {

        $data = $request->validate([
            'codigo' => 'required|max:3',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_factor' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($caracteristica, $request) {
                    $caracteristicas = Caracteristica::where('id_factor', $request['id_factor'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $caracteristicas->count(); $i++){
                        $peso_total -= $caracteristicas[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    $total_editar = $peso_total + $caracteristica->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        if ($caracteristica->id_factor != $data['id_factor'] || $caracteristica->peso != $data['peso']) {

            $factor1 = Factor::find($caracteristica->id_factor);
            $factor2 = Factor::find($data['id_factor']);

            $caracteristica = Caracteristica::findOrFail($caracteristica->id);
            $caracteristica->codigo = $data['codigo'];
            $caracteristica->nombre = $data['nombre'];
            $caracteristica->descripcion = $data['descripcion'];
            $caracteristica->id_factor = $data['id_factor'];
            $caracteristica->peso = $data['peso'];

            $caracteristica->save();

            $this->progresos($factor1);
            $this->progresos($factor2);
        }else {

            $caracteristica = Caracteristica::findOrFail($caracteristica->id);
            $caracteristica->codigo = $data['codigo'];
            $caracteristica->nombre = $data['nombre'];
            $caracteristica->descripcion = $data['descripcion'];
            $caracteristica->id_factor = $data['id_factor'];
            $caracteristica->peso = $data['peso'];

            $caracteristica->save();
        }

        return redirect()->action([CaracteristicaController::class, 'index']);
    }

    public function estado(Request $request, Caracteristica $caracteristica)
    {

        $caracteristicas = Caracteristica::where('id_factor', $caracteristica->id_factor)->where('estado', 'Activado')->get();

        $peso_total = 100;

        for($i = 0; $i < $caracteristicas->count(); $i++){
            $peso_total -= $caracteristicas[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $caracteristica = Caracteristica::findOrFail($caracteristica->id);

        if($caracteristica->estado=='Desactivado'){
            //Leer el nuevo estado
            if($caracteristica->peso <= $peso_total){
                $caracteristica->estado='Activado';
                $caracteristica->save();

                $factor = Factor::find($caracteristica->id_factor);
                $this->progresos($factor);

                return redirect()->route('caracteristicas.index')->with('status_estado', 'si')->with('tipo', 'Activado');
            }else {
                return redirect()->route('caracteristicas.index')->with('status_estado', 'no');
            }
        }
        else{
            $caracteristica->estado='Desactivado';
            $caracteristica->save();

            $factor = Factor::find($caracteristica->id_factor);
            $this->progresos($factor);

            return redirect()->route('caracteristicas.index')->with('status_estado', 'si')->with('tipo', 'Desactivado');
        }
    }

    public function peso(Request $request){
        $caracteristicas = Caracteristica::where('id_factor', $request['id_factor'])->where('estado', 'Activado')->get();
        $peso_total = 100;

        for($i = 0; $i < $caracteristicas->count(); $i++){
            $peso_total -= $caracteristicas[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $factor = Factor::findOrFail($request['id_factor']);
        return response()->json([
            'peso_total' => $peso_total,
            'factor' => $factor->nombre
        ], 200);
    }

    public function progresos(Factor $factor) {
        // Calculamos el progreso del aspecto

        $caracteristicas=Caracteristica::where('id_factor', $factor->id)->where('estado','Activado')->get();

        $cont_progreso = 0;

        foreach($caracteristicas as $caracteristica){
            $cont_progreso += (($caracteristica->progreso * $caracteristica->peso) / 100);
        }

        $factor->progreso = $cont_progreso;
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


        return $factor;
    }
}
