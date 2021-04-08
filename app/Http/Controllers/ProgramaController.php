<?php

namespace App\Http\Controllers;

use App\Facultad;
use App\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programas = Programa::all();
        return view('programas.index', compact('programas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facultades = Facultad::all(['id', 'nombre']);
        return view('programas.create', compact('facultades'));
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
            'id_facultad' => 'required',
        ]);

        DB::table('programas')->insert([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_facultad' => $data['id_facultad']
        ]);

        return redirect()->action([ProgramaController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Programa  $programa
     * @return \Illuminate\Http\Response
     */
    public function show(Programa $programa)
    {
        return view('programas.show', compact('programa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Programa  $programa
     * @return \Illuminate\Http\Response
     */
    public function edit(Programa $programa)
    {
        $facultades = Facultad::all(['id', 'nombre']);
        return view('programas.edit', compact('facultades', 'programa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Programa  $programa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programa $programa)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_facultad' => 'required',
        ]);

        $programa = Programa::findorFail($programa->id);
        $programa->nombre = $data['nombre'];
        $programa->descripcion = $data['descripcion'];
        $programa->id_facultad = $data['id_facultad'];

        $programa->save();

        return redirect()->action([ProgramaController::class, 'index']);
    }

    public function estado(Request $request, Programa $programa)
    {
        if($programa->estado=='Desactivado'){
            //Leer el nuevo estado
            $programa->estado='Activado';
            $programa->save();
        }
        else{
            $programa->estado='Desactivado';
            $programa->save();
        }
        return redirect()->action([ProgramaController::class, 'index']);
    }
}
