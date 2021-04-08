<?php

namespace App\Http\Controllers;

use App\Universidad;
use Illuminate\Http\Request;
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
        $universidades = Universidad::all();
        $contarUniversidades = DB::table('universidads')->count();
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
        if ($contarUniversidades === 0) {
            return view('universidades.create');
        }else {
            return abort('404');
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
            'vision' => 'required | string'
        ]);

        DB::table('universidads')->insert([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'mision' => $data['mision'],
            'vision' => $data['vision']
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
        return view('universidades.edit', compact('universidad'));
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
            'vision' => 'required | string'
        ]);

        $universidad = Universidad::findorFail($universidad->id);
        $universidad->nombre = $data['nombre'];
        $universidad->descripcion = $data['descripcion'];
        $universidad->mision = $data['mision'];
        $universidad->vision = $data['vision'];

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
