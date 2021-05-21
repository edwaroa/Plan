<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Factor;
use App\Proyecto;
use Carbon\Carbon;
use App\TipoFactor;
use App\Universidad;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class FactorController extends Controller
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
        if(Auth::user()->rol->nombre == "Decano"){
            $factores = Factor::all();
        }else {
            $factores = Factor::where('estado', 'Activado')->get();
        }

        return view('factores.index', compact('factores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $proyectos = Proyecto::all(['id', 'nombre']);
            $factores = Factor::all();

            $total_factores = $factores->count();
            $peso_total = 100;

            for($i = 0; $i < $total_factores; $i++){
                $peso_total -= $factores[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

            return view('factores.create', compact('proyectos', 'peso_total'));
        }else {
            return redirect()->action([FactorController::class, 'index']);
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
            'codigo' => 'required|unique:factors,codigo|integer',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_proyecto' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($request){
                    $factores = Factor::where('id_proyecto', $request['id_proyecto'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $factores->count(); $i++){
                        $peso_total -= $factores[$i]->peso;
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

        DB::table('factors')->insert([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_proyecto' => $data['id_proyecto'],
            'peso' => $data['peso'],
            'progreso' => 0
        ]);

        return redirect()->action([FactorController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factor  $factor
     * @return \Illuminate\Http\Response
     */
    public function show(Factor $factor)
    {
        return view('factores.show', compact('factor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factor  $factor
     * @return \Illuminate\Http\Response
     */
    public function edit(Factor $factor)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $proyectos = Proyecto::all(['id', 'nombre']);
            $factores = Factor::where('id_proyecto', $factor->id_proyecto)->where('estado', 'Activado')->get();

            $total_factores = $factores->count();
            $peso_total = 100;

            for($i = 0; $i < $total_factores; $i++){
                $peso_total -= $factores[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

            return view('factores.edit', compact('proyectos', 'factor', 'peso_total'));
        }else {
            return redirect()->action([FactorController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factor  $factor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factor $factor)
    {
        $data = $request->validate([
            'codigo' => 'required|integer',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_proyecto' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($factor, $request) {
                    $factores = Factor::where('id_proyecto', $request['id_proyecto'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $factores->count(); $i++){
                        $peso_total -= $factores[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    $total_editar = $peso_total + $factor->peso;

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

        if($factor->id_proyecto != $data['id_proyecto'] || $factor->peso != $data['peso']) {

            $proyecto1 = Proyecto::find($factor->id_proyecto);
            $proyecto2 = Proyecto::find($request['id_proyecto']);

            $factor = Factor::findOrFail($factor->id);
            $factor->codigo = $data['codigo'];
            $factor->nombre = $data['nombre'];
            $factor->descripcion = $data['descripcion'];
            $factor->id_proyecto = $data['id_proyecto'];
            $factor->peso = $data['peso'];

            $factor->save();

            $this->progresos($proyecto1);
            $this->progresos($proyecto2);
        }else {

            $factor = Factor::findOrFail($factor->id);
            $factor->codigo = $data['codigo'];
            $factor->nombre = $data['nombre'];
            $factor->descripcion = $data['descripcion'];
            $factor->id_proyecto = $data['id_proyecto'];
            $factor->peso = $data['peso'];

            $factor->save();
        }

        return redirect()->action([FactorController::class, 'index']);
    }

    public function estado(Request $request, Factor $factor)
    {
        $factores = Factor::where('id_proyecto', $factor->id_proyecto)->where('estado', 'Activado')->get();

        $peso_total = 100;

        for($i = 0; $i < $factores->count(); $i++){
            $peso_total -= $factores[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $factor = Factor::findOrFail($factor->id);

        if($factor->estado=='Desactivado'){
            //Leer el nuevo estado
            if($factor->peso <= $peso_total){
                $factor->estado='Activado';
                $factor->save();

                $proyecto = Proyecto::find($factor->id_proyecto);
                $this->progresos($proyecto);

                return redirect()->route('factores.index')->with('status_estado', 'si')->with('tipo', 'Activado');
            }else {
                return redirect()->route('factores.index')->with('status_estado', 'no');
            }
        }
        else{
            $factor->estado='Desactivado';
            $factor->save();

            $proyecto = Proyecto::find($factor->id_proyecto);
            $this->progresos($proyecto);

            return redirect()->route('factores.index')->with('status_estado', 'si')->with('tipo', 'Desactivado');
        }
    }

    public function peso(Request $request){
        $factores = Factor::where('id_proyecto', $request['id_proyecto'])->where('estado', 'Activado')->get();
        $peso_total = 100;

        for($i = 0; $i < $factores->count(); $i++){
            $peso_total -= $factores[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $proyecto = Proyecto::findOrFail($request['id_proyecto']);
        return response()->json([
            'peso_total' => $peso_total,
            'proyecto' => $proyecto->nombre
        ], 200);
    }

    public function progresos(Proyecto $proyecto) {
        // Calculamos el progreso del aspecto

        $factores=Factor::where('id_proyecto', $proyecto->id)->where('estado','Activado')->get();

        $cont_progreso = 0;

        foreach($factores as $caracteristica){
            $cont_progreso += (($caracteristica->progreso * $caracteristica->peso) / 100);
        }

        $proyecto->progreso = $cont_progreso;
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


        return $proyecto;
    }

    public function exportar()
    {
        $factores = Factor::all();

        $universidad = Universidad::all();
        $fecha = Carbon::now()->format('Y-m-d');
        $aleatorio = rand(0, getrandmax());
        $codigo = 'RP-' . $aleatorio;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.reporteFactores', compact('factores', 'universidad', 'fecha', 'codigo'))->setPaper('a4', 'landscape');

        return $pdf ->download('factores.pdf');
    }
}
