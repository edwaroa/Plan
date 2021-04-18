<?php

use App\Http\Controllers\FacultadController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\UniversidadController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () { return view('welcome'); });

Route::get('/home/corporacion', function () { return view('homeCorporacion'); })->name('homeCorporacion');

Auth::routes();

Route::get('/manuales', 'HomeController@manuales')->name('manuales');
Route::get('/manuales/descarga/{nombre}', 'HomeController@descargar')->name('manuales.descargar');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/roles', 'RolController@index')->name('roles.index');
Route::get('/roles/{rol}', 'RolController@show')->name('roles.show');

// Usuarios
Route::get('/usuarios', 'UsuarioController@index')->name('usuarios.index');
Route::get('/usuarios/create', 'UsuarioController@create')->name('usuarios.create');
Route::post('/usuarios', 'UsuarioController@store')->name('usuarios.store');
Route::get('/usuarios/{user}', 'UsuarioController@show')->name('usuarios.show');
Route::get('/usuarios/{user}/edit', 'UsuarioController@edit')->name('usuarios.edit');
Route::put('/usuarios/{user}', 'UsuarioController@update')->name('usuarios.update');
Route::get('/usuarios/{user}/eliminar', 'UsuarioController@eliminar')->name('usuarios.delete');

Route::post('/usuarios/{user}', 'UsuarioController@estado')->name('usuarios.estado');
Route::delete('usuarios/{user}', 'UsuarioController@destroy')->name('usuarios.destroy');


Auth::routes();
//Desactivar el register

// Perfil
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

// Planes
Route::get('/planes', [PlanController::class, 'index'])->name('planes.index');
Route::get('/planes/create', [PlanController::class, 'create'])->name('planes.create');
Route::post('/planes', [PlanController::class, 'store'])->name('planes.store');
Route::get('/planes/{plan}', [PlanController::class, 'show'])->name('planes.show');
Route::get('/planes/{plan}/edit', [PlanController::class, 'edit'])->name('planes.edit');
Route::put('/planes/{plan}', [PlanController::class, 'update'])->name('planes.update');
Route::post('/planes/{plan}', [PlanController::class, 'estado'])->name('planes.estado');

// Proyectos
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
Route::get('/proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('proyectos.show');
Route::get('/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit');
Route::put('/proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
// Route::post('/proyectos/{proyecto}', [ProyectoController::class, 'estado'])->name('proyectos.estado');

// Universidades
Route::get('/universidades', [UniversidadController::class, 'index'])->name('universidades.index');
Route::get('/universidades/create', [UniversidadController::class, 'create'])->name('universidades.create');
Route::post('/universidades', [UniversidadController::class, 'store'])->name('universidades.store');
Route::get('/universidades/{universidad}', [UniversidadController::class, 'show'])->name('universidades.show');
Route::get('/universidades/{universidad}/edit', [UniversidadController::class, 'edit'])->name('universidades.edit');
Route::put('/universidades/{universidad}', [UniversidadController::class, 'update'])->name('universidades.update');
Route::post('/universidades/{universidad}', [UniversidadController::class, 'estado'])->name('universidades.estado');

// Facultades
Route::get('/facultades', [FacultadController::class, 'index'])->name('facultades.index');
Route::get('/facultades/create', [FacultadController::class, 'create'])->name('facultades.create');
Route::post('/facultades', [FacultadController::class, 'store'])->name('facultades.store');
Route::get('/facultades/{facultad}', [FacultadController::class, 'show'])->name('facultades.show');
Route::get('/facultades/{facultad}/edit', [FacultadController::class, 'edit'])->name('facultades.edit');
Route::put('/facultades/{facultad}', [FacultadController::class, 'update'])->name('facultades.update');
Route::post('/facultades/{facultad}', [FacultadController::class, 'estado'])->name('facultades.estado');

// Programas
Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
Route::get('/programas/create', [ProgramaController::class, 'create'])->name('programas.create');
Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
Route::get('/programas/{programa}', [ProgramaController::class, 'show'])->name('programas.show');
Route::get('/programas/{programa}/edit', [ProgramaController::class, 'edit'])->name('programas.edit');
Route::put('/programas/{programa}', [ProgramaController::class, 'update'])->name('programas.update');
Route::post('/programas/{programa}', [ProgramaController::class, 'estado'])->name('programas.estado');

Route::get('/about', function () {
    return view('about');
})->name('about');

