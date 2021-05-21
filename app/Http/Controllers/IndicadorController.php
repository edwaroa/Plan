<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Factor;
use App\Aspecto;
use App\Proyecto;
use App\Indicador;
use Carbon\Carbon;
use App\Universidad;
use App\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class IndicadorController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->rol->nombre == "Decano"){
            $indicadores = Indicador::all();
        }else {
            $indicadores = Indicador::where('estado', 'Activado')->get();
        }
        return view('indicadores.index', compact('indicadores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $aspectos = Aspecto::all(['id', 'nombre', 'codigo']);

            return view('indicadores.create', compact('aspectos'));
        }else {
            return redirect()->action([IndicadorController::class, 'index']);
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
            'codigo' => 'required|unique:indicadors,codigo|string|max:7',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_aspecto' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail)  use($request){
                    $indicadores = Indicador::where('id_aspecto', $request['id_aspecto'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $indicadores->count(); $i++){
                        $peso_total -= $indicadores[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                },
                function($attribute, $value, $fail) {
                    if($value <= 0) {
                        $fail("El " . $attribute . " no puede ser menor o igual a 0");
                    }
                }
            ]
        ]);

        DB::table('indicadors')->insert([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_aspecto' => $data['id_aspecto'],
            'peso' => $data['peso'],
            'progreso' => 0,
        ]);

        return redirect()->action([IndicadorController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function show(Indicador $indicador)
    {
        return view('indicadores.show', compact('indicador'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function edit(Indicador $indicador)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $aspectos = Aspecto::all(['id', 'nombre', 'codigo']);
            $indicadores = Indicador::where('id_aspecto', $indicador->id_aspecto)->where('estado', 'Activado')->get();

            $total_indicadores = $indicadores->count();
            $peso_total = 100;

            for($i = 0; $i < $total_indicadores; $i++){
                $peso_total -= $indicadores[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

            return view('indicadores.edit', compact('aspectos', 'indicador', 'peso_total'));
        }else {
            return redirect()->action([AspectoController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Indicador $indicador)
    {
        $data = $request->validate([
            'codigo' => 'required|max:7',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_aspecto' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($indicador, $request) {
                    $indicadores = Indicador::where('id_aspecto', $request['id_aspecto'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $indicadores->count(); $i++){
                        $peso_total -= $indicadores[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    $total_editar = $peso_total + $indicador->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                },
                function($attribute, $value, $fail) {
                    if($value <= 0) {
                        $fail("El " . $attribute . " no puede ser menor o igual a 0");
                    }
                }
            ]
        ]);

        if ($indicador->id_aspecto != $data['id_aspecto'] || $indicador->peso != $data['peso']) {

            $aspecto1 = Aspecto::find($indicador->id_aspecto);
            $aspecto2 = Aspecto::find($data['id_aspecto']);

            $indicador = indicador::findOrFail($indicador->id);
            $indicador->codigo = $data['codigo'];
            $indicador->nombre = $data['nombre'];
            $indicador->descripcion = $data['descripcion'];
            $indicador->id_aspecto = $data['id_aspecto'];
            $indicador->peso = $data['peso'];

            $indicador->save();

            $this->progresos($aspecto1);
            $this->progresos($aspecto2);

        }else {
            $indicador = indicador::findOrFail($indicador->id);
            $indicador->codigo = $data['codigo'];
            $indicador->nombre = $data['nombre'];
            $indicador->descripcion = $data['descripcion'];
            $indicador->id_aspecto = $data['id_aspecto'];
            $indicador->peso = $data['peso'];

            $indicador->save();
        }

        return redirect()->action([IndicadorController::class, 'index']);
    }

    public function estado(Request $request, Indicador $indicador)
    {
        $indicadores = Indicador::where('id_aspecto', $indicador->id_aspecto)->where('estado', 'Activado')->get();

        $peso_total = 100;

        for($i = 0; $i < $indicadores->count(); $i++){
            $peso_total -= $indicadores[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $indicador = Indicador::findOrFail($indicador->id);

        if($indicador->estado=='Desactivado'){
            //Leer el nuevo estado
            if($indicador->peso <= $peso_total){
                $indicador->estado='Activado';
                $indicador->save();

                $aspectos = Aspecto::findOrFail($indicador->id_aspecto);

                $this->progresos($aspectos);

                return redirect()->route('indicadores.index')->with('status_estado', 'si')->with('tipo', 'Activado');
            }else {
                return redirect()->route('indicadores.index')->with('status_estado', 'no');
            }
        }
        else{
            $indicador->estado='Desactivado';
            $indicador->save();

            $aspectos = Aspecto::findOrFail($indicador->id_aspecto);
            $this->progresos($aspectos);

            return redirect()->route('indicadores.index')->with('status_estado', 'si')->with('tipo', 'Desactivado');
        }
    }

    public function peso(Request $request){
        $indicadores = Indicador::where('id_aspecto', $request['id_aspecto'])->where('estado', 'Activado')->get();
        $peso_total = 100;

        for($i = 0; $i < $indicadores->count(); $i++){
            $peso_total -= $indicadores[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $aspecto = Aspecto::findOrFail($request['id_aspecto']);
        return response()->json([
            'peso_total' => $peso_total,
            'aspecto' => $aspecto->nombre
        ], 200);
    }

    public function progresos(Aspecto $aspecto) {
        // Calculamos el progreso del aspecto

        $indicadores=Indicador::where('id_aspecto', $aspecto->id)->where('estado','Activado')->get();

        $cont_progreso = 0;

        foreach($indicadores as $indicador){
            $cont_progreso += (($indicador->progreso * $indicador->peso) / 100);
        }

        $aspecto->progreso = $cont_progreso;
        $aspecto->save();

        // Características

        $caracteristica = Caracteristica::findOrFail($aspecto->id_caracteristica);
        $aspectos = Aspecto::where('id_caracteristica', $caracteristica->id)->where('estado', 'Activado')->get();

        $carac_progreso = 0;

        foreach($aspectos as $aspecto) {
            $carac_progreso += (($aspecto->progreso * $aspecto->peso) / 100);
        }

        $caracteristica->progreso = $carac_progreso;
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


        return $aspecto;
    }

    public function exportar()
    {
        $indicadores = Indicador::all();

        $universidad = Universidad::all();
        $fecha = Carbon::now()->format('Y-m-d');
        $aleatorio = rand(0, getrandmax());
        $codigo = 'RP-' . $aleatorio;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.reporteIndicadores', compact('indicadores', 'universidad', 'fecha', 'codigo'))->setPaper('a4', 'landscape');

        return $pdf ->download('indicadores.pdf');
    }
}
