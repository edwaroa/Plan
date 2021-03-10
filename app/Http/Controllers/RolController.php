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

    public function show(Rol $rol)
    {
        $usuarios= User::where('id_rol',$rol->id)->get();
        return view('roles.show',compact('rol', 'usuarios'));
    }
}
