<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Programa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'fecha_final' => 'required | date | date_format:Y-m-d'
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
        return view('planes.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        //
    }
}
