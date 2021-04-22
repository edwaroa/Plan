<?php

namespace App\Http\Controllers;

use App\Factor;
use App\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $caracteristicas = Caracteristica::all();

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
                    $caracteristicas = Caracteristica::where('id_factor', $request['id_factor'])->get();
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
            $caracteristicas = Factor::all();

            $total_caracteristicas = $caracteristicas->count();
            $peso_total = 100;

            for($i = 0; $i < $total_caracteristicas; $i++){
                $peso_total -= $caracteristicas[$i]->peso;
            }

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
                    $caracteristicas = Caracteristica::where('id_factor', $request['id_factor'])->get();
                    $peso_total = 100;

                    for($i = 0; $i < $caracteristicas->count(); $i++){
                        $peso_total -= $caracteristicas[$i]->peso;
                    }

                    $total_editar = $peso_total + $caracteristica->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        $caracteristica = Caracteristica::findOrFail($caracteristica->id);
        $caracteristica->codigo = $data['codigo'];
        $caracteristica->nombre = $data['nombre'];
        $caracteristica->descripcion = $data['descripcion'];
        $caracteristica->id_factor = $data['id_factor'];
        $caracteristica->peso = $data['peso'];

        $caracteristica->save();

        return redirect()->action([CaracteristicaController::class, 'index']);
    }

    public function estado(Request $request, Caracteristica $caracteristica)
    {
        $caracteristica = Caracteristica::findOrFail($caracteristica->id);

        if($caracteristica->estado=='Desactivado'){
            //Leer el nuevo estado
            $caracteristica->estado='Activado';
            $caracteristica->save();
        }
        else{
            $caracteristica->estado='Desactivado';
            $caracteristica->save();
        }
        return redirect()->action([CaracteristicaController::class, 'index']);
    }
}
