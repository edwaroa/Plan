<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->rol->nombre == 'Decano') {
            $roles = Rol::all();
            return view('roles.index')->with('roles',$roles);
        }else {
            return redirect()->action([HomeController::class, 'index']);
        }
    }

    public function show(Rol $rol)
    {
        if(Auth::user()->rol->nombre == 'Decano') {
            $usuarios= User::where('id_rol',$rol->id)->get();
            return view('roles.show',compact('rol', 'usuarios'));
        }else {
            return redirect()->action([HomeController::class, 'index']);
        }
    }
}
