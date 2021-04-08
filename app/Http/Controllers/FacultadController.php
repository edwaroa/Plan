<?php

namespace App\Http\Controllers;

use App\Facultad;
use App\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facultades = Facultad::all();
        return view('facultades.index', compact('facultades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $universidades = Universidad::all(['id', 'nombre']);
        return view('facultades.create', compact('universidades'));
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
            'id_universidad' => 'required',
        ]);

        DB::table('facultads')->insert([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_universidad' => $data['id_universidad']
        ]);

        return redirect()->action([FacultadController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Facultad  $facultad
     * @return \Illuminate\Http\Response
     */
    public function show(Facultad $facultad)
    {
        return view('facultades.show', compact('facultad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Facultad  $facultad
     * @return \Illuminate\Http\Response
     */
    public function edit(Facultad $facultad)
    {
        $universidades = Universidad::all(['id', 'nombre']);
        return view('facultades.edit', compact('facultad', 'universidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facultad  $facultad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facultad $facultad)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_universidad' => 'required',
        ]);

        $facultad = Facultad::findorFail($facultad->id);
        $facultad->nombre = $data['nombre'];
        $facultad->descripcion = $data['descripcion'];
        $facultad->id_universidad = $data['id_universidad'];

        $facultad->save();

        return redirect()->action([FacultadController::class, 'index']);
    }

    public function estado(Request $request, Facultad $facultad)
    {
        if($facultad->estado=='Desactivado'){
            //Leer el nuevo estado
            $facultad->estado='Activado';
            $facultad->save();
        }
        else{
            $facultad->estado='Desactivado';
            $facultad->save();
        }
        return redirect()->action([FacultadController::class, 'index']);
    }
}
