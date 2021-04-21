<?php

namespace App\Http\Controllers;

use App\Factor;
use App\Proyecto;
use App\TipoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

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
        $factores = Factor::all();

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
            $tiposFactores = TipoFactor::all(['id', 'nombre']);
            $proyectos = Proyecto::all(['id', 'nombre']);
            $factores = Factor::all();

            $total_factores = $factores->count();
            $peso_total = 100;

            for($i = 0; $i < $total_factores; $i++){
                $peso_total -= $factores[$i]->peso;
            }

            return view('factores.create', compact('tiposFactores', 'proyectos', 'peso_total'));
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
            'id_tipo_factor' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) {
                    $factores = Proyecto::all();
                    $peso_total = 100;

                    for($i = 0; $i < $factores->count(); $i++){
                        $peso_total -= $factores[$i]->peso;
                    }

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        DB::table('factors')->insert([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_proyecto' => $data['id_proyecto'],
            'id_tipo_factor' => $data['id_tipo_factor'],
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
            $tiposFactores = TipoFactor::all(['id', 'nombre']);
            $proyectos = Proyecto::all(['id', 'nombre']);
            $factores = Factor::all();

            $total_factores = $factores->count();
            $peso_total = 100;

            for($i = 0; $i < $total_factores; $i++){
                $peso_total -= $factores[$i]->peso;
            }

            return view('factores.edit', compact('proyectos', 'factor', 'tiposFactores', 'peso_total'));
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
            'codigo' => 'required',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_proyecto' => 'required',
            'id_tipo_factor' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($factor) {
                    $factores = Factor::all();
                    $peso_total = 100;

                    for($i = 0; $i < $factores->count(); $i++){
                        $peso_total -= $factores[$i]->peso;
                    }

                    $total_editar = $peso_total + $factor->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        $factor = Factor::findOrFail($factor->id);
        $factor->codigo = $data['codigo'];
        $factor->nombre = $data['nombre'];
        $factor->descripcion = $data['descripcion'];
        $factor->id_proyecto = $data['id_proyecto'];
        $factor->id_tipo_factor = $data['id_tipo_factor'];
        $factor->peso = $data['peso'];

        $factor->save();

        return redirect()->action([FactorController::class, 'index']);
    }

    public function estado(Request $request, Factor $factor)
    {
        $factor = Factor::findOrFail($factor->id);

        if($factor->estado=='Desactivado'){
            //Leer el nuevo estado
            $factor->estado='Activado';
            $factor->save();
        }
        else{
            $factor->estado='Desactivado';
            $factor->save();
        }
        return redirect()->action([FactorController::class, 'index']);
    }
}
