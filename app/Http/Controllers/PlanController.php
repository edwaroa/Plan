<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Programa;
use Carbon\Carbon;
use App\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Psy\CodeCleaner\ReturnTypePass;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
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
            // Trae los planes de mejoramiento insertados en la base de datos
            $planes = Plan::all();
        }else {
            $planes = Plan::where('estado', 'Activado')->get();
        }

        return view('planes.index', compact('planes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $programas = Programa::all(['id', 'nombre']);
            $fecha_actual = date('Y-m-d');

            return view('planes.create', compact('programas', 'fecha_actual'));
        }else {
            return redirect()->action([PlanController::class, 'index']);
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
            'nombre' => 'required | string',
            'descripcion' => 'required | string',
            'objetivo_general' => 'required | string',
            'objetivos_especificos' => 'required | string',
            'id_programa' => 'required',
            'fecha_inicio' => 'required | date | date_format:Y-m-d',
            'fecha_final' => [
                'required',
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use($request) {
                    if($value < $request['fecha_inicio']){
                        $fail($attribute.' no puede ser menor a la fecha de inicio');
                    }
                }
            ]
        ]);

        DB::table('plans')->insert([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'objetivo_general' => $data['objetivo_general'],
            'objetivos_especificos' => $data['objetivos_especificos'],
            'id_programa' => $data['id_programa'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_final' => $data['fecha_final'],
            'progreso' => 0
        ]);

        return redirect()->action([PlanController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        $fecha_inicio = Carbon::parse($plan->fecha_inicio);
        $fecha_final = Carbon::parse($plan->fecha_final);

        $dias_fechas = $fecha_inicio->diffInDays($fecha_final);

        return view('planes.show', compact('plan', 'dias_fechas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $programas = Programa::all(['id', 'nombre']);

            return view('planes.edit', compact('programas', 'plan'));
        }else {
            return redirect()->action([PlanController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'nombre' => 'required | string',
            'descripcion' => 'required | string',
            'objetivo_general' => 'required | string',
            'objetivos_especificos' => 'required | string',
            'id_programa' => 'required',
            'fecha_inicio' => 'required | date | date_format:Y-m-d',
            'fecha_final' => 'required | date | date_format:Y-m-d'
        ]);

        $plan = Plan::findOrFail($plan->id);
        $plan->nombre = $data['nombre'];
        $plan->descripcion = $data['descripcion'];
        $plan->objetivo_general = $data['objetivo_general'];
        $plan->objetivos_especificos = $data['objetivos_especificos'];
        $plan->id_programa = $data['id_programa'];
        $plan->fecha_inicio = $data['fecha_inicio'];
        $plan->fecha_final = $data['fecha_final'];

        $plan->save();

        return redirect()->action([PlanController::class, 'index']);
    }

    public function estado(Request $request, Plan $plan)
    {
        if($plan->estado=='Desactivado'){
            //Leer el nuevo estado
            $plan->estado='Activado';
            $plan->save();
        }
        else{
            $plan->estado='Desactivado';
            $plan->save();
        }
        return redirect()->action([PlanController::class, 'index']);
    }

    public function exportar()
    {
        $planes = Plan::all();

        $universidad = Universidad::all();
        $fecha = Carbon::now()->format('Y-m-d');
        $aleatorio = rand(0, getrandmax());
        $codigo = 'RP-' . $aleatorio;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.reportePlanes', compact('planes', 'universidad', 'fecha', 'codigo'))->setPaper('a4', 'landscape');

        return $pdf ->download('planes.pdf');
    }

    public function exportarPlan($id) {
        $planes = Plan::where('id', $id)->get();

        $universidad = Universidad::all();
        $fecha = Carbon::now()->format('Y-m-d');
        $aleatorio = rand(0, getrandmax());
        $codigo = 'RP-' . $aleatorio;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.planes.reporte', compact('planes', 'universidad', 'fecha', 'codigo'))->setPaper('a4', 'landscape');

        return $pdf ->download('plan_'. $id . '.pdf');
    }
}
