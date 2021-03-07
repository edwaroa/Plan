@extends('layouts.admin')

@section('content')

<div class="container">
<h1 class="h3 mb-2 text-gray-800">Miembros</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
               <!-- <div class="card-header">{{ __('Ver información del usuario') }}</div>-->
               <div class="card-header">{{ __('Información general') }}</div>
                   <form method="POST" action="" enctype="multipart/form-data" novalidate>
                        @csrf

                        @method('PUT')
                        <h1 class="text-center">Usuario {{$user->name}}</h1>
          
                        <div class="form-group row">
                            <label for="typedocument" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de documento') }}</label>

                            <div class="col-md-6">
                            <input id="typedocument" type="text" class="form-control" name="typedocument" value="{{$user->typedocument}}" readonly="readonly" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="document" class="col-md-4 col-form-label text-md-right">{{ __('N° documento') }}</label>

                            <div class="col-md-6">
                                <input id="document" type="text" class="form-control" name="document" value="{{$user->document}}" readonly="readonly" autofocus>
                            </div>
                        </div>
              
            
                    <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" readonly="readonly" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo electronico') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}" readonly="readonly">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rol" class="col-md-4 col-form-label text-md-right">{{ __('Rol') }}</label>

                            <div class="col-md-6">
                            <input id="rol" type="text" class="form-control" name="rol" value="{{$user->rol->nombre}}" readonly="readonly" autofocus>
                            </div>
                        </div>
                    
                        <!-- <div id="app" class="content">
                                <eliminar-usuario usuario-id="{{$user->id}}" ></eliminar-usuario>
                        </div> -->
                        <div class="form-group row mb-1">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">{{ __('Eliminar') }}</span>
                                </button>                
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="{{route('usuarios.index')}}" class="btn btn-secondary btn-icon-split">
                                    <span class="icon text-white-50">Volver</span></a>
                            </div>
                        </div>
                    </form>   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection