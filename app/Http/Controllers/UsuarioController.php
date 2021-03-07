<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use App\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

        if(Auth::user()->rol->nombre=='Decano'){
            //Muestra las actividades de todos los usuarios
            $usuarios = User::all();
        }else{
            //Muestra las actividades del usuario logeado
            $usuarios=User::where('estado','Activado')->get();
        }
        return view('usuarios.index')->with('usuarios',$usuarios);
    }

    public function create(){

        //Traer con modelo
        $roles=Rol::all(['id','nombre']);

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request){
       //Validacion de los campos del formulario
        $data=request()->validate([
            'tipo_documento' => ['required', 'string'],
            'documento' => ['required', 'numeric', 'unique:users'],
            'nombre' => ['required', 'string', 'max:60'],
            'apellido' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'contraseña' => ['required', 'string', 'min:8','max:12'],
            'confirmar_contraseña' => ['min:8','max:12','required_with:contraseña','same:contraseña'],
            'rol' => 'required',
        ]);

        //Almacenar en la base de datos
        DB::table('users')->insert([
            'tipo_documento' => $data['tipo_documento'],
            'documento' => $data['documento'],
            'nombre' => $data['nombre'],
            'apellido'=> $data['apellido'],
            'email' => $data['email'],
            'password' => Hash::make($data['contraseña']),
            'id_rol' => $data['rol']
        ]);

        return redirect()->action('UsuarioController@index');
    }
    public function show(User $user){

        if($user->estado==='Desactivado'){
            return abort(404);
        }
        return view('usuarios.show', compact('user'));

    }
    public function edit(User $user){
        $roles=Rol::all(['id','nombre']);
        return view('usuarios.edit',compact('roles','user'));
    }

    public function update(Request $request,User $user){

       //Validacion de los campos del formulario
       $request->validate([
        'tipo_documento' => ['required','string', 'max:255'],
        'documento' => 'string|unique:users,documento,' .$user->id,
        'nombre' => ['required', 'string', 'max:255'],
        'apellido' => ['required', 'string', 'max:255'],
        'email' =>'required|string|email|max:255|unique:users,email,'.$user->id,
        'rol' => 'required',
    ]);

        //Asignar los valores
        $user = User::findOrFail($user->id);
        $user->tipo_documento=$request['tipo_documento'];
        $user->documento=$request['documento'];
        $user->nombre=$request['nombre'];
        $user->apellido=$request['apellido'];
        $user->email=$request['email'];
        $user->id_rol=$request['rol'];
        $user->save();
        return redirect()->action('UsuarioController@index');
    }

    public function estado(Request $request,User $user)
    {
        //Autorizar para que pueda modificar
       // $this->authorize('update', $actividad);
        if($user->estado=='Desactivado'){
            //Leer el nuevo estado
            $user->estado='Activado';
            $user->save();
        }
        else{
            $user->estado='Desactivado';
            $user->save();
        }
        return redirect()->action('UsuarioController@index');
    }

}
