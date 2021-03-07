<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Rol::all();
        return view('roles.index')->with('roles',$roles);
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        //Validacion de los campos del formulario
        $data=request()->validate([
            'nombre' => ['required', 'string', 'max:100','unique:rols'],
            'estado'=> 'required'
        ]);

        //Almacenar en la base de datos sin modelo
        DB::table('rols')->insert([
            'nombre' => $data['nombre'],
            'estado' => $data['estado']
        ]);

        return redirect()->action('RolController@index');
    }

    public function show(Rol $rol)
    {
        $usuarios= User::where('id_rol',$rol->id)->get();
        return view('roles.show',compact('rol', 'usuarios'));
    }

    public function edit(Rol $rol)
    {
        return view('roles.edit',compact('rol'));
    }

    public function update(Request $request, Rol $rol)
    {
             //Validando el formulario de actualización
             $data=request()->validate([
                'nombre' => ['required', 'string', 'max:100','unique:rols,nombre,'.$rol->id],
                'estado' => 'required',
            ]);

            //Asignando valores en la tabla
            $rol->nombre=$data['nombre'];
            $rol->estado=$data['estado'];
            $rol->save();
            return redirect()->action('RolController@index');
    }

    public function estado(Request $request,Rol $rol)
    {
        //Autorizar para que pueda modificar
       // $this->authorize('update', $actividad);
        if($rol->estado=='Desactivado'){
            //Leer el nuevo estado
            $rol->estado='Activado';
            $rol->save();
        }
        else{
            $rol->estado='Desactivado';
            $rol->save();
        }
        return redirect()->action('RolController@index');
    }
}
