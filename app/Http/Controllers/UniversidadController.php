<?php

namespace App\Http\Controllers;

use App\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniversidadController extends Controller
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
        $contarUniversidades = DB::table('universidads')->count();
        if(Auth::user()->rol->nombre == 'Decano'){
            $universidades = Universidad::all();
        }else {
            $universidades = Universidad::where('estado', 'Activado')->get();
        }

        return view('universidades.index', compact('universidades', 'contarUniversidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contarUniversidades = DB::table('universidads')->count();
        if ($contarUniversidades === 0 && Auth::user()->rol->nombre == 'Decano') {

            return view('universidades.create');
        }else {
            return redirect()->action([UniversidadController::class, 'index']);
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
            'mision' => 'required | string',
            'vision' => 'required | string',
            'nit' => 'required | unique:universidads',
            'telefono' => 'required | numeric',
            'direccion' => 'required | string',
            'logo' => 'required | image'
        ]);

        // Variable para la ruta del logo
        $archivo = $request->file('logo');
        $ruta_logo = $archivo->store('upload-universidad', 'public');


        DB::table('universidads')->insert([
            'nit' => $data['nit'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'mision' => $data['mision'],
            'vision' => $data['vision'],
            'logo' => $ruta_logo,
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion']
        ]);

        return redirect()->action([UniversidadController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Universidad  $universidad
     * @return \Illuminate\Http\Response
     */
    public function show(Universidad $universidad)
    {
        return view('universidades.show', compact('universidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Universidad  $universidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Universidad $universidad)
    {
        if (Auth::user()->rol->nombre == 'Decano') {

            return view('universidades.edit', compact('universidad'));
        }else {
            return redirect()->action([UniversidadController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Universidad  $universidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Universidad $universidad)
    {
        $data = $request->validate([
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'mision' => 'required | string',
            'vision' => 'required | string',
            'nit' => 'required | unique:universidads,nit,' . $universidad->id,
            'telefono' => 'required | numeric',
            'direccion' => 'required | string',
            'logo' => 'image'
        ]);

        $universidad = Universidad::findorFail($universidad->id);
        $universidad->nit = $data['nit'];
        $universidad->nombre = $data['nombre'];
        $universidad->descripcion = $data['descripcion'];
        $universidad->mision = $data['mision'];
        $universidad->vision = $data['vision'];
        $universidad->telefono = $data['telefono'];
        $universidad->direccion = $data['direccion'];

        if($request['logo']){
            $archivo = $request->file('logo');
            $ruta_logo = $archivo->store('upload-universidad', 'public');
            $universidad->logo = $ruta_logo;
        }


        $universidad->save();

        return redirect()->action([UniversidadController::class, 'index']);
    }

    public function estado(Request $request,Universidad $universidad)
    {
        if($universidad->estado=='Desactivado'){
            //Leer el nuevo estado
            $universidad->estado='Activado';
            $universidad->save();
        }
        else{
            $universidad->estado='Desactivado';
            $universidad->save();
        }
        return redirect()->action([UniversidadController::class, 'index']);
    }
}
