<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use App\Actividad;
use App\Aspecto;
use App\Caracteristica;
use App\Evidencia;
use App\Factor;
use App\Indicador;
use App\Proyecto;
use App\TipoFactor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
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
            $actividades = Actividad::all();
        }else {
            $actividades = Actividad::join('actividad_user', 'actividad_user.actividad_id', '=', 'actividads.id')
            ->join('users', 'users.id', '=', 'actividad_user.user_id')
            ->where('actividad_user.user_id', '=', Auth::user()->id)
            ->select('actividads.*')->get();
        }

        // var_dump($actividades);

        return view('actividades.index', compact('actividades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $indicadores = Indicador::all(['id', 'nombre', 'codigo']);
            $usuarios = User::paginate(10);
            $fecha_actual = Carbon::now()->format('Y-m-d');

            return view('actividades.create', compact('indicadores', 'fecha_actual', 'usuarios'));
        }else {
            return redirect()->action([ActividadController::class, 'index']);
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
            'id_indicador' => 'required',
            'fecha_inicio' => [
                'required',
                function($attribute, $value, $fail) use($request) {
                    if($request['id_indicador']){
                        $fecha_plan = Plan::join('proyectos', 'proyectos.id_plan', '=', 'plans.id')
                        ->join('factors', 'factors.id_proyecto', '=', 'proyectos.id')
                        ->join('caracteristicas', 'caracteristicas.id_factor', '=', 'factors.id')
                        ->join('aspectos', 'aspectos.id_caracteristica', '=', 'caracteristicas.id')
                        ->join('indicadors', 'indicadors.id_aspecto', '=', 'aspectos.id')
                        ->where('indicadors.id', $request['id_indicador'])
                        ->select('plans.fecha_inicio as fecha_inicio')->get();

                        if($fecha_plan[0]->fecha_inicio > $value){
                            $fail("La fecha de inicio no puede ser anterior a la fecha de inicio del plan correspondiente");
                        }
                    }
                }
            ],
            'tiempo_entrega' => [
                'required',
                function($attribute, $value, $fail) use($request) {
                    if($request['id_indicador']){
                        $fecha_plan = Plan::join('proyectos', 'proyectos.id_plan', '=', 'plans.id')
                        ->join('factors', 'factors.id_proyecto', '=', 'proyectos.id')
                        ->join('caracteristicas', 'caracteristicas.id_factor', '=', 'factors.id')
                        ->join('aspectos', 'aspectos.id_caracteristica', '=', 'caracteristicas.id')
                        ->join('indicadors', 'indicadors.id_aspecto', '=', 'aspectos.id')
                        ->where('indicadors.id', $request['id_indicador'])
                        ->select('plans.fecha_final as fecha_final')->get();

                        if($fecha_plan[0]->fecha_final < $value){
                            $fail("La fecha de final no puede ser superior a la fecha final del plan correspondiente");
                        }
                    }
                }
            ],
            'usuarios' => 'required|array|min:1',
            'usuarios.*' => 'required|integer|distinct|min:1',
            'peso' => [
                'required',
                function($attribute, $value, $fail)  use($request){
                    $actividades = Actividad::where('id_indicador', $request['id_indicador'])->get();
                    $peso_total = 100;

                    for($i = 0; $i < $actividades->count(); $i++){
                        $peso_total -= $actividades[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        $actividad = Actividad::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_indicador' => $data['id_indicador'],
            'fecha_inicio' => $data['fecha_inicio'],
            'tiempo_entrega' => $data['tiempo_entrega'],
            'peso' => $data['peso']
        ]);

        $actividad->users()->sync($request->get('usuarios'));

        return redirect()->action([ActividadController::class, 'index']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function show(Actividad $actividad)
    {
        $fecha_inicio = Carbon::parse($actividad->fecha_inicio);
        $tiempo_entrega = Carbon::parse($actividad->tiempo_entrega);

        $dias_fechas = $fecha_inicio->diffInDays($tiempo_entrega);

        $evidencias= Evidencia::where('actividad_id',$actividad->id)->get();

        return view('actividades.show', compact('actividad', 'dias_fechas', 'evidencias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function edit(Actividad $actividad)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $indicadores = Indicador::all(['id', 'nombre', 'codigo']);
            $usuarios = User::paginate(10);
            $fecha_actual = Carbon::now()->format('Y-m-d');

            $actividades = Actividad::where('id_indicador', $actividad->id_indicador)->get();

            $total_actividades = $actividades->count();
            $peso_total = 100;

            for($i = 0; $i < $total_actividades; $i++){
                $peso_total -= $actividades[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

            $actividad_user = [];

            foreach($actividad->users as $usuario) {
                $actividad_user[] = $usuario->id;
            }

            return view('actividades.edit', compact('indicadores', 'actividad_user', 'usuarios', 'actividad', 'peso_total'));
        }else {
            return redirect()->action([ActividadController::class, 'index']);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actividad $actividad)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_indicador' => 'required',
            'fecha_inicio' => [
                'required',
                function($attribute, $value, $fail) use($request) {
                    if($request['id_indicador']){
                        $fecha_plan = Plan::join('proyectos', 'proyectos.id_plan', '=', 'plans.id')
                        ->join('factors', 'factors.id_proyecto', '=', 'proyectos.id')
                        ->join('caracteristicas', 'caracteristicas.id_factor', '=', 'factors.id')
                        ->join('aspectos', 'aspectos.id_caracteristica', '=', 'caracteristicas.id')
                        ->join('indicadors', 'indicadors.id_aspecto', '=', 'aspectos.id')
                        ->where('indicadors.id', $request['id_indicador'])
                        ->select('plans.fecha_inicio as fecha_inicio')->get();

                        if($fecha_plan[0]->fecha_inicio > $value){
                            $fail("La fecha de inicio no puede ser anterior a la fecha de inicio del plan correspondiente");
                        }
                    }
                }
            ],
            'tiempo_entrega' => [
                'required',
                function($attribute, $value, $fail) use($request) {
                    if($request['id_indicador']){
                        $fecha_plan = Plan::join('proyectos', 'proyectos.id_plan', '=', 'plans.id')
                        ->join('factors', 'factors.id_proyecto', '=', 'proyectos.id')
                        ->join('caracteristicas', 'caracteristicas.id_factor', '=', 'factors.id')
                        ->join('aspectos', 'aspectos.id_caracteristica', '=', 'caracteristicas.id')
                        ->join('indicadors', 'indicadors.id_aspecto', '=', 'aspectos.id')
                        ->where('indicadors.id', $request['id_indicador'])
                        ->select('plans.fecha_final as fecha_final')->get();

                        if($fecha_plan[0]->fecha_final < $value){
                            $fail("La fecha de final no puede ser superior a la fecha final del plan correspondiente");
                        }
                    }
                }
            ],
            'usuarios' => 'required|array|min:1',
            'usuarios.*' => 'required|integer|distinct|min:1',
            'peso' => [
                'required',
                function($attribute, $value, $fail)  use($request, $actividad){
                    $actividades = Actividad::where('id_indicador', $request['id_indicador'])->get();
                    $peso_total = 100;

                    for($i = 0; $i < $actividades->count(); $i++){
                        $peso_total -= $actividades[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    $total_editar = $peso_total + $actividad->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        if($actividad->id_indicador != $data['id_indicador'] || $actividad->peso != $data['peso']){

            $indicador1=Indicador::find($actividad->id_indicador);
            $indicador2=Indicador::find($data['id_indicador']);

            $actividad->update([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'id_indicador' => $data['id_indicador'],
                'fecha_inicio' => $data['fecha_inicio'],
                'tiempo_entrega' => $data['tiempo_entrega'],
                'peso' => $data['peso']
            ]);

            $actividad->users()->sync($request->get('usuarios'));

            $this->progresos($indicador1);
            $this->progresos($indicador2);
        }else {
            $actividad->update([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'id_indicador' => $data['id_indicador'],
                'fecha_inicio' => $data['fecha_inicio'],
                'tiempo_entrega' => $data['tiempo_entrega'],
                'peso' => $data['peso']
            ]);

            $actividad->users()->sync($request->get('usuarios'));
        }

        return redirect()->action([ActividadController::class, 'index']);
    }

    public function fecha(Request $request)
    {
        // $indicador = Indicador::where('id', $request['id_indicador'])->get();
        $fecha_plan = Plan::join('proyectos', 'proyectos.id_plan', '=', 'plans.id')
        ->join('factors', 'factors.id_proyecto', '=', 'proyectos.id')
        ->join('caracteristicas', 'caracteristicas.id_factor', '=', 'factors.id')
        ->join('aspectos', 'aspectos.id_caracteristica', '=', 'caracteristicas.id')
        ->join('indicadors', 'indicadors.id_aspecto', '=', 'aspectos.id')
        ->where('indicadors.id', $request['id_indicador'])
        ->select('plans.fecha_inicio as fecha_inicio', 'plans.fecha_final as fecha_final', 'indicadors.nombre as indicador')->get();

        $actividad = Actividad::where('id_indicador', $request['id_indicador'])->get();
        $peso_total = 100;

        for($i = 0; $i < $actividad->count(); $i++){
            $peso_total -= $actividad[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $actividades=Actividad::where('id_indicador', $request['id_indicador'])->where('estado','Avalada')->get();

        $cont_peso = 0;

        foreach($actividades as $actividad){
            $cont_peso += $actividad->peso;
        }

        $cont_peso = round($cont_peso, 2);

        return response()->json([
            'fecha_plan' => $fecha_plan,
            'peso_total' => $peso_total,
            'cont_peso' => $cont_peso
        ], 200);
    }

    public function avalar(Actividad $actividad, Request $request) {
        $data = $request->validate([
            'estado' => 'required',
            'comentario' => 'required|max:300'
        ]);

        if (Auth::user()->rol->nombre == "Decano") {
            $actividad->estado = $data['estado'];
            $actividad->comentario = $data['comentario'];
            $actividad->save();

            $indicador = Indicador::find($actividad->id_indicador);

            $this->progresos($indicador);

            return back()->with('estado', 'La actividad ha sido ' . $data['estado'] . ' correctamente!');
        }else {
            return back()->with('error', 'No tiene permisos para ' . $data['estado'] . ' esta actividad');
        }
    }

    public function progresos(Indicador $indicador) {
        // Calculamos el progreso del indicador segun las actividades que tenga avaladas

        $actividades=Actividad::where('id_indicador', $indicador->id)->where('estado','Avalada')->get();

        $cont_progreso = 0;

        foreach($actividades as $actividad){
            $cont_progreso += $actividad->peso;
        }

        $indicador->progreso = $cont_progreso;
        $indicador->save();

        // Aspectos

        $aspecto = Aspecto::findOrFail($indicador->id_aspecto);
        $indicadores = Indicador::where('id_aspecto', $aspecto->id)->where('estado', 'Activado')->get();

        $asp_progreso = 0;

        foreach($indicadores as $indicador) {
            $asp_progreso += (($indicador->progreso * $indicador->peso) / 100);
        }

        $aspecto->progreso = $asp_progreso;
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


        return $indicador;
    }
}
