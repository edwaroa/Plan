<?php

namespace App\Http\Controllers;

use App\Evidencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EvidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'descripcion' => 'required|string|max:100',
            'url_documento' => 'required|file',
            'actividad_id' => 'required'
        ]);

        //Almacenar el archivo
        $archivo=$request->file('url_documento');
        //Creamos la evidencia en el servidor y le asignamos el nombre del archivo origilal
        $url_documento= $archivo->storeAs('evidencias',$archivo->getClientOriginalName());

        DB::table('evidencias')->insert([
            'descripcion' => $data['descripcion'],
            'url_documento' => $url_documento,
            'actividad_id' => $data['actividad_id'],
            'usuario_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return back()->with('estado', 'Evidencia subida correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evidencia = Evidencia::findOrFail($id);

        return response()->download(storage_path("app/public/{$evidencia->url_documento}"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Evidencia $evidencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evidencia $evidencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evidencia $evidencia)
    {
        Storage::delete($evidencia->nombre);
        $evidencia->delete();

        return back()->with('estado','La evidencia fue eliminada correctamente');
    }
}
