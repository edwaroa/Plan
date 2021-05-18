<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AspectoController;
use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\FacultadController;
use App\Http\Controllers\IndicadorController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TipoFactorController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/home/corporacion', function () { return view('homeCorporacion'); })->name('homeCorporacion');

});

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
// Peso
Route::post('/pesopro', [ProyectoController::class, 'peso'])->name('proyectos.peso');
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
Route::get('/proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('proyectos.show');
Route::get('/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit');
Route::put('/proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
Route::post('/proyectos/{proyecto}', [ProyectoController::class, 'estado'])->name('proyectos.estado');

// Factores
// Peso
Route::post('/pesofac', [FactorController::class, 'peso']);
Route::get('/factores', [FactorController::class, 'index'])->name('factores.index');
Route::get('/factores/create', [FactorController::class, 'create'])->name('factores.create');
Route::post('/factores', [FactorController::class, 'store'])->name('factores.store');
Route::get('/factores/{factor}', [FactorController::class, 'show'])->name('factores.show');
Route::get('/factores/{factor}/edit', [FactorController::class, 'edit'])->name('factores.edit');
Route::put('/factores/{factor}', [FactorController::class, 'update'])->name('factores.update');
Route::post('/factores/{factor}', [FactorController::class, 'estado'])->name('factores.estado');

// Características
Route::get('caracteristicas/exportar', [CaracteristicaController::class, 'exportar'])->name('caracteristicas.exportar');
// Peso
Route::post('/pesocar', [CaracteristicaController::class, 'peso']);
Route::get('/caracteristicas', [CaracteristicaController::class, 'index'])->name('caracteristicas.index');
Route::get('/caracteristicas/create', [CaracteristicaController::class, 'create'])->name('caracteristicas.create');
Route::post('/caracteristicas', [CaracteristicaController::class, 'store'])->name('caracteristicas.store');
Route::get('/caracteristicas/{caracteristica}', [CaracteristicaController::class, 'show'])->name('caracteristicas.show');
Route::get('/caracteristicas/{caracteristica}/edit', [CaracteristicaController::class, 'edit'])->name('caracteristicas.edit');
Route::put('/caracteristicas/{caracteristica}', [CaracteristicaController::class, 'update'])->name('caracteristicas.update');
Route::post('/caracteristicas/{caracteristica}', [CaracteristicaController::class, 'estado'])->name('caracteristicas.estado');

// Aspectos
Route::get('aspectos/exportar', [AspectoController::class, 'exportar'])->name('aspectos.exportar');
// Peso
Route::post('/pesoasp', [AspectoController::class, 'peso']);
Route::get('/aspectos', [AspectoController::class, 'index'])->name('aspectos.index');
Route::get('/aspectos/create', [AspectoController::class, 'create'])->name('aspectos.create');
Route::post('/aspectos', [AspectoController::class, 'store'])->name('aspectos.store');
Route::get('/aspectos/{aspecto}', [AspectoController::class, 'show'])->name('aspectos.show');
Route::get('/aspectos/{aspecto}/edit', [AspectoController::class, 'edit'])->name('aspectos.edit');
Route::put('/aspectos/{aspecto}', [AspectoController::class, 'update'])->name('aspectos.update');
Route::post('/aspectos/{aspecto}', [AspectoController::class, 'estado'])->name('aspectos.estado');

// Indicadores
Route::get('indicadores/exportar', [IndicadorController::class, 'exportar'])->name('indicadores.exportar');
// Peso
Route::post('/pesoind', [IndicadorController::class, 'peso']);
Route::get('/indicadores', [IndicadorController::class, 'index'])->name('indicadores.index');
Route::get('/indicadores/create', [IndicadorController::class, 'create'])->name('indicadores.create');
Route::post('/indicadores', [IndicadorController::class, 'store'])->name('indicadores.store');
Route::get('/indicadores/{indicador}', [IndicadorController::class, 'show'])->name('indicadores.show');
Route::get('/indicadores/{indicador}/edit', [IndicadorController::class, 'edit'])->name('indicadores.edit');
Route::put('/indicadores/{indicador}', [IndicadorController::class, 'update'])->name('indicadores.update');
Route::post('/indicadores/{indicador}', [IndicadorController::class, 'estado'])->name('indicadores.estado');


// Actividades

Route::get('actividades/exportar', [ActividadController::class, 'exportar'])->name('actividades.exportar');
Route::get('/actividades', [ActividadController::class, 'index'])->name('actividades.index');
Route::get('/actividades/create', [ActividadController::class, 'create'])->name('actividades.create');
Route::post('/actividades', [ActividadController::class, 'store'])->name('actividades.store');
Route::get('/actividades/{actividad}', [ActividadController::class, 'show'])->name('actividades.show');
Route::get('/actividades/{actividad}/edit', [ActividadController::class, 'edit'])->name('actividades.edit');
Route::put('/actividades/{actividad}', [ActividadController::class, 'update'])->name('actividades.update');
Route::post('/actividades/{actividad}', [ActividadController::class, 'estado'])->name('actividades.estado');
Route::post('/actividades/{actividad}', [ActividadController::class, 'avalar'])->name('actividades.avalar');

// fecha del plan
Route::post('/fecha', [ActividadController::class, 'fecha']);

// Evidencias
Route::post('/evidencias/store', [EvidenciaController::class, 'store'])->name('evidencias.store');
Route::get('/evidencias/descarga/{id}', [EvidenciaController::class, 'show'])->name('evidencias.descargar');
Route::delete('/evidencias/{evidencia}', [EvidenciaController::class, 'destroy'])->name('evidencias.destroy');

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

