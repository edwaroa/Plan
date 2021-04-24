<?php

namespace App\Http\Controllers;

use App\Aspecto;
use App\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AspectoController extends Controller
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
        $aspectos = Aspecto::all();

        return view('aspectos.index', compact('aspectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $caracteristicas = Caracteristica::all(['id', 'nombre', 'codigo']);

            return view('aspectos.create', compact('caracteristicas'));
        }else {
            return redirect()->action([AspectoController::class, 'index']);
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
            'codigo' => 'required|unique:aspectos,codigo|string|max:5',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_caracteristica' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail)  use($request){
                    $aspectos = Aspecto::where('id_caracteristica', $request['id_caracteristica'])->get();
                    $peso_total = 100;

                    for($i = 0; $i < $aspectos->count(); $i++){
                        $peso_total -= $aspectos[$i]->peso;
                    }

                    if($peso_total < $value){
                        $fail("El " .$attribute . " no puede ser mayor que el total disponible");
                    }
                }
            ]
        ]);

        DB::table('aspectos')->insert([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'id_caracteristica' => $data['id_caracteristica'],
            'peso' => $data['peso'],
            'progreso' => 0,
        ]);

        return redirect()->action([AspectoController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aspecto  $aspecto
     * @return \Illuminate\Http\Response
     */
    public function show(Aspecto $aspecto)
    {
        return view('aspectos.show', compact('aspecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aspecto  $aspecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Aspecto $aspecto)
    {
        if(Auth::user()->rol->nombre == 'Decano'){
            $caracteristicas = Caracteristica::all(['id', 'nombre', 'codigo']);
            $aspectos = Aspecto::all();

            $total_aspectos = $aspectos->count();
            $peso_total = 100;

            for($i = 0; $i < $total_aspectos; $i++){
                $peso_total -= $aspectos[$i]->peso;
            }

            return view('aspectos.edit', compact('caracteristicas', 'aspecto', 'peso_total'));
        }else {
            return redirect()->action([AspectoController::class, 'index']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aspecto  $aspecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aspecto $aspecto)
    {
        $data = $request->validate([
            'codigo' => 'required|max:5',
            'nombre' => 'required | string | max:255',
            'descripcion' => 'required | string',
            'id_caracteristica' => 'required',
            'peso' => [
                'required',
                function($attribute, $value, $fail) use($aspecto, $request) {
                    $aspectos = Aspecto::where('id_caracteristica', $request['id_caracteristica'])->get();
                    $peso_total = 100;

                    for($i = 0; $i < $aspectos->count(); $i++){
                        $peso_total -= $aspectos[$i]->peso;
                    }

                    $total_editar = $peso_total + $aspecto->peso;

                    if($total_editar < $value){
                        $fail("No se puede agregar más " .$attribute . " del total disponible");
                    }
                }
            ]
        ]);

        $aspecto = Aspecto::findOrFail($aspecto->id);
        $aspecto->codigo = $data['codigo'];
        $aspecto->nombre = $data['nombre'];
        $aspecto->descripcion = $data['descripcion'];
        $aspecto->id_caracteristica = $data['id_caracteristica'];
        $aspecto->peso = $data['peso'];

        $aspecto->save();

        return redirect()->action([AspectoController::class, 'index']);
    }

    public function estado(Request $request, Aspecto $aspecto)
    {
        $aspecto = Aspecto::findOrFail($aspecto->id);

        if($aspecto->estado=='Desactivado'){
            //Leer el nuevo estado
            $aspecto->estado='Activado';
            $aspecto->save();
        }
        else{
            $aspecto->estado='Desactivado';
            $aspecto->save();
        }
        return redirect()->action([AspectoController::class, 'index']);
    }

    public function peso(Request $request){
        $aspecto = Aspecto::where('id_caracteristica', $request['id_caracteristica'])->get();
        $peso_total = 100;

        for($i = 0; $i < $aspecto->count(); $i++){
            $peso_total -= $aspecto[$i]->peso;
        }

        $caracteristica = Caracteristica::findOrFail($request['id_caracteristica']);
        return response()->json([
            'peso_total' => $peso_total,
            'caracteristica' => $caracteristica->nombre
        ], 200);
    }
}
