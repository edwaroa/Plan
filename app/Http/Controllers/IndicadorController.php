<?php

namespace App\Http\Controllers;

use App\Aspecto;
use App\Indicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
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

                    $total_editar = $peso_total + $indicador->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        $indicador = indicador::findOrFail($indicador->id);
        $indicador->codigo = $data['codigo'];
        $indicador->nombre = $data['nombre'];
        $indicador->descripcion = $data['descripcion'];
        $indicador->id_aspecto = $data['id_aspecto'];
        $indicador->peso = $data['peso'];

        $indicador->save();

        return redirect()->action([IndicadorController::class, 'index']);
    }

    public function estado(Request $request, Indicador $indicador)
    {
        $indicadores = Indicador::where('id_aspecto', $indicador->id_aspecto)->where('estado', 'Activado')->get();

        $peso_total = 100;

        for($i = 0; $i < $indicadores->count(); $i++){
            $peso_total -= $indicadores[$i]->peso;
        }

        $indicador = Indicador::findOrFail($indicador->id);

        if($indicador->estado=='Desactivado'){
            //Leer el nuevo estado
            if($indicador->peso <= $peso_total){
                $indicador->estado='Activado';
                $indicador->save();

                return redirect()->route('indicadores.index')->with('status_estado', 'si')->with('tipo', 'Activado');
            }else {
                return redirect()->route('indicadores.index')->with('status_estado', 'no');
            }
        }
        else{
            $indicador->estado='Desactivado';
            $indicador->save();

            return redirect()->route('indicadores.index')->with('status_estado', 'si')->with('tipo', 'Desactivado');
        }
    }

    public function peso(Request $request){
        $indicadores = Indicador::where('id_aspecto', $request['id_aspecto'])->where('estado', 'Activado')->get();
        $peso_total = 100;

        for($i = 0; $i < $indicadores->count(); $i++){
            $peso_total -= $indicadores[$i]->peso;
        }

        $aspecto = Aspecto::findOrFail($request['id_aspecto']);
        return response()->json([
            'peso_total' => $peso_total,
            'aspecto' => $aspecto->nombre
        ], 200);
    }
}
