<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use App\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\EstadoUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Notifications\DesactivarUsuario;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

        if(Auth::user()->rol->nombre=='Decano'){
            //Muestra todos los usuarios
            $usuarios = User::all();
        }else{
            //Muestra los usuarios activados
            $usuarios=User::where('estado','Activado')->get();
        }

        return view('usuarios.index')->with('usuarios',$usuarios);
    }

    public function create(){
        if(Auth::user()->rol->nombre == 'Decano') {
            //Traer con modelo
            $roles=Rol::all(['id','nombre']);

            return view('usuarios.create', compact('roles'));
        }else {
            return redirect()->action([UsuarioController::class, 'index']);
        }
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
            'imagen' => 'required | image',
            'telefono' => 'required|numeric|max:10',
            'genero' => 'required'
        ]);

        // Variable para la ruta de la imagen
        $archivo = $request->file('imagen');
        $ruta_imagen = $archivo->store('upload-usuarios', 'public');

        // resize de la imagen
        // $img = Image::make(public_path("/storage/{$ruta_imagen}"))->resize(500, 700);
        // $img->save();

        // Fecha actual
        $fecha = Carbon::now();

        //Almacenar en la base de datos
        DB::table('users')->insert([
            'tipo_documento' => $data['tipo_documento'],
            'documento' => $data['documento'],
            'nombre' => $data['nombre'],
            'apellido'=> $data['apellido'],
            'email' => $data['email'],
            'password' => Hash::make($data['contraseña']),
            'id_rol' => $data['rol'],
            'imagen' => $ruta_imagen,
            'telefono' => $data['telefono'],
            'genero' => $data['genero'],
            'created_at' => $fecha,
            'updated_at' => $fecha
        ]);

        return redirect()->action('UsuarioController@index');
    }
    public function show(User $user){
        return view('usuarios.show', compact('user'));
    }

    public function edit(User $user){
        if(Auth::user()->rol->nombre == 'Decano') {
            // Trayendo el id y el nombre de todos los roles
            $roles=Rol::all(['id','nombre']);
            return view('usuarios.edit',compact('roles','user'));
        }else {
            return redirect()->action([UsuarioController::class, 'index']);
        }
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
            'telefono' => 'required|numeric',
            'genero' => 'required'
        ]);

        //Asignar los valores
        $user = User::findOrFail($user->id);
        $user->tipo_documento=$request['tipo_documento'];
        $user->documento=$request['documento'];
        $user->nombre=$request['nombre'];
        $user->apellido=$request['apellido'];
        $user->email=$request['email'];
        $user->id_rol=$request['rol'];
        $user->telefono=$request['telefono'];
        $user->genero=$request['genero'];

        // Si el usuario sube una nueva imagen
        if(request('imagen')){
            $archivo = $request->file('imagen');
            $ruta_imagen = $archivo->store('upload-usuarios', 'public');
            $user->imagen = $ruta_imagen;
        }

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
            $user->notify(new EstadoUsuario($user));
            $user->save();
        }
        else{
            $user->estado='Desactivado';
            $user->notify(new DesactivarUsuario($user));
            $user->save();
        }
        return redirect()->action('UsuarioController@index');
    }

}
