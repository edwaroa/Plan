<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use App\Actividad;
use App\Evidencia;
use App\Indicador;
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
            'usuarios.*' => 'required|integer|distinct|min:1'
        ]);

        $actividad = Actividad::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_indicador' => $data['id_indicador'],
            'fecha_inicio' => $data['fecha_inicio'],
            'tiempo_entrega' => $data['tiempo_entrega']
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

            $actividad_user = [];

            foreach($actividad->users as $usuario) {
                $actividad_user[] = $usuario->id;
            }

            return view('actividades.edit', compact('indicadores', 'actividad_user', 'usuarios', 'actividad'));
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
            'usuarios.*' => 'required|integer|distinct|min:1'
        ]);

        $actividad->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_indicador' => $data['id_indicador'],
            'fecha_inicio' => $data['fecha_inicio'],
            'tiempo_entrega' => $data['tiempo_entrega']
        ]);

        $actividad->users()->sync($request->get('usuarios'));

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

        return response()->json([
            'fecha_plan' => $fecha_plan
        ], 200);
    }
}
