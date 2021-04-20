<?php

namespace App\Http\Controllers;

use App\Facultad;
use App\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgramaController extends Controller
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
        if(Auth::user()->rol->nombre == 'Decano') {
            $programas = Programa::all();
        }else {
            $programas = Programa::where('estado', 'Activado')->get();
        }

        return view('programas.index', compact('programas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano') {
            $facultades = Facultad::all(['id', 'nombre']);
            return view('programas.create', compact('facultades'));
        }else {
            return redirect()->action([ProgramaController::class, 'index']);
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
        if(Auth::user()->rol->nombre == 'Decano') {
            $facultades = Facultad::all(['id', 'nombre']);
            return view('programas.edit', compact('facultades', 'programa'));
        }else {
            return redirect()->action([ProgramaController::class, 'index']);
        }
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
