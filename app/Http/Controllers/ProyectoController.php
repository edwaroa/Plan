<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Proyecto;
use Carbon\Carbon;
use App\Universidad;
use App\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
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

            $peso_total = round($peso_total, 2);

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
                function($attribute, $value, $fail) use($request) {
                    $proyectos = Proyecto::where('id_plan', $request['id_plan'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $proyectos->count(); $i++){
                        $peso_total -= $proyectos[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

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
            $proyectos = Proyecto::where('id_plan', $proyecto->id_plan)->where('estado', 'Activado')->get();
            $total_proyectos = $proyectos->count();
            $peso_total = 100;

            for($i = 0; $i < $total_proyectos; $i++){
                $peso_total -= $proyectos[$i]->peso;
            }

            $peso_total = round($peso_total, 2);

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
                function($attribute, $value, $fail) use($proyecto, $request) {
                    $proyectos = Proyecto::where('id_plan', $request['id_plan'])->where('estado', 'Activado')->get();
                    $peso_total = 100;

                    for($i = 0; $i < $proyectos->count(); $i++){
                        $peso_total -= $proyectos[$i]->peso;
                    }

                    $peso_total = round($peso_total, 2);

                    $total_editar = $peso_total + $proyecto->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        if($proyecto->id_plan != $data['id_plan'] || $proyecto->peso != $data['peso']){

            $plan1 = Plan::find($proyecto->id_plan);
            $plan2 = Plan::find($data['id_plan']);

            $proyecto = Proyecto::findOrFail($proyecto->id);
            $proyecto->nombre = $data['nombre'];
            $proyecto->descripcion = $data['descripcion'];
            $proyecto->objetivo_general = $data['objetivo_general'];
            $proyecto->objetivos_especificos = $data['objetivos_especificos'];
            $proyecto->id_plan = $data['id_plan'];
            $proyecto->peso = $data['peso'];

            $proyecto->save();

            $this->progresos($plan1);
            $this->progresos($plan2);
        }else {

            $proyecto = Proyecto::findOrFail($proyecto->id);
            $proyecto->nombre = $data['nombre'];
            $proyecto->descripcion = $data['descripcion'];
            $proyecto->objetivo_general = $data['objetivo_general'];
            $proyecto->objetivos_especificos = $data['objetivos_especificos'];
            $proyecto->id_plan = $data['id_plan'];
            $proyecto->peso = $data['peso'];

            $proyecto->save();
        }

        return redirect()->action([ProyectoController::class, 'index']);
    }

    public function estado(Request $request, Proyecto $proyecto)
    {
        $proyectos = Proyecto::where('id_plan', $proyecto->id_plan)->where('estado', 'Activado')->get();

        $peso_total = 100;

        for($i = 0; $i < $proyectos->count(); $i++){
            $peso_total -= $proyectos[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $proyecto = Proyecto::findOrFail($proyecto->id);

        if($proyecto->estado=='Desactivado'){
            //Leer el nuevo estado
            if($proyecto->peso <= $peso_total){
                $proyecto->estado='Activado';
                $proyecto->save();

                $plan = Plan::find($proyecto->id_plan);
                $this->progresos($plan);

                return redirect()->route('proyectos.index')->with('status_estado', 'si')->with('tipo', 'Activado');
            }else {
                return redirect()->route('proyectos.index')->with('status_estado', 'no');
            }
        }
        else{
            $proyecto->estado='Desactivado';
            $proyecto->save();

            $plan = Plan::find($proyecto->id_plan);
            $this->progresos($plan);

            return redirect()->route('proyectos.index')->with('status_estado', 'si')->with('tipo', 'Desactivado');
        }
    }

    public function peso(Request $request){
        $proyectos = Proyecto::where('id_plan', $request['id_plan'])->where('estado', 'Activado')->get();
        $peso_total = 100;

        for($i = 0; $i < $proyectos->count(); $i++){
            $peso_total -= $proyectos[$i]->peso;
        }

        $peso_total = round($peso_total, 2);

        $plan = Plan::findOrFail($request['id_plan']);
        return response()->json([
            'peso_total' => $peso_total,
            'plan' => $plan->nombre
        ], 200);
    }

    public function progresos(Plan $plan) {
        // Calculamos el progreso del aspecto

        $proyectos = Proyecto::where('id_plan', $plan->id)->where('estado','Activado')->get();

        $cont_progreso = 0;

        foreach($proyectos as $proyecto){
            $cont_progreso += (($proyecto->progreso * $proyecto->peso) / 100);
        }

        $plan->progreso = $cont_progreso;
        $plan->save();

        return $plan;
    }

    public function exportar()
    {
        $proyectos = Proyecto::all();

        $universidad = Universidad::all();
        $fecha = Carbon::now()->format('Y-m-d');
        $aleatorio = rand(0, getrandmax());
        $codigo = 'RP-' . $aleatorio;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.reporteProyectos', compact('proyectos', 'universidad', 'fecha', 'codigo'))->setPaper('a4', 'landscape');

        return $pdf ->stream('proyectos.pdf');
    }
}
