<?php

namespace App\Http\Controllers;

use App\TipoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TipoFactorController extends Controller
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
            $tiposFactores = TipoFactor::all();
        }else {
            $tiposFactores = TipoFactor::where('estado','Activado')->get();
        }

        return view('tipos_factores.index', compact('tiposFactores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $tiposFactores = TipoFactor::all();
            $total_tiposFactores = $tiposFactores->count();
            $porcentaje_total = 100;

            for($i = 0; $i < $total_tiposFactores; $i++){
                $porcentaje_total -= $tiposFactores[$i]->porcentaje;
            }

            return view('tipos_factores.create', compact('porcentaje_total'));
        }else {
            return redirect()->action([TipoFactorController::class, 'index']);
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
            'porcentaje' => [
                'required',
                function($attribute, $value, $fail) {
                    $tiposFactores = TipoFactor::all();
                    $porcentaje_total = 100;

                    for($i = 0; $i < $tiposFactores->count(); $i++){
                        $porcentaje_total -= $tiposFactores[$i]->porcentaje;
                    }

                    if($porcentaje_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        DB::table('tipo_factors')->insert([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'porcentaje' => $data['porcentaje'],
            'progreso' => 0
        ]);

        return redirect()->action([TipoFactorController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoFactor  $tipoFactor
     * @return \Illuminate\Http\Response
     */
    public function show(TipoFactor $tipoFactor, $tipofactor)
    {
        $tipoFactor = TipoFactor::findOrFail($tipofactor);
        return view('tipos_factores.show', compact('tipoFactor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoFactor  $tipoFactor
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoFactor $tipoFactor, $tipofactor)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $tipoFactor = TipoFactor::findOrFail($tipofactor);
            $tiposFactores = TipoFactor::all();
            $total_tiposFactores = $tiposFactores->count();
            $porcentaje_total = 100;

            for($i = 0; $i < $total_tiposFactores; $i++){
                $porcentaje_total -= $tiposFactores[$i]->porcentaje;
            }

            return view('tipos_factores.edit', compact('tipoFactor', 'porcentaje_total'));
        }else {
            return redirect()->action([ProyectoController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoFactor  $tipoFactor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoFactor $tipoFactor, $tipofactor)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'porcentaje' => [
                'required',
                function($attribute, $value, $fail) use($tipoFactor, $tipofactor) {
                    $tiposFactores = TipoFactor::all();
                    $tipoFactor = TipoFactor::findOrFail($tipofactor);
                    $peso_total = 100;

                    for($i = 0; $i < $tiposFactores->count(); $i++){
                        $peso_total -= $tiposFactores[$i]->porcentaje;
                    }

                    $total_editar = $peso_total + $tipoFactor->porcentaje;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        $tipoFactor = TipoFactor::findOrFail($tipofactor);
        $tipoFactor->nombre = $data['nombre'];
        $tipoFactor->descripcion = $data['descripcion'];
        $tipoFactor->porcentaje = $data['porcentaje'];

        $tipoFactor->save();

        return redirect()->action([TipoFactorController::class, 'index']);
    }

    public function estado(Request $request, TipoFactor $tipoFactor, $tipofactor)
    {
        $tipoFactor = TipoFactor::findOrFail($tipofactor);

        if($tipoFactor->estado=='Desactivado'){
            //Leer el nuevo estado
            $tipoFactor->estado='Activado';
            $tipoFactor->save();
        }
        else{
            $tipoFactor->estado='Desactivado';
            $tipoFactor->save();
        }
        return redirect()->action([TipoFactorController::class, 'index']);
    }
}
