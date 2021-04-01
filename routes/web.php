<?php

use App\Http\Controllers\PlanController;
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

Route::get('/about', function () {
    return view('about');
})->name('about');
