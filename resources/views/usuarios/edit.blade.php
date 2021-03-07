@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Miembros</h1>
    <div class="col-lg-10 order-lg-1">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="m-0 font-weight-bold text-primary">{{ __('Editar Miembro') }}</div>
            </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('usuarios.update', ['user'=>$user->id])}}" enctype="multipart/form-data" autocomplete="off" novalidate>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <h6 class="heading-small text-muted mb-4">Información del miembro</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="tipo_documento">Tipo de documento<span class="small text-danger">*</span></label>
                                        <select id="tipo_documento" name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror">
                                            <option value="{{$user->tipo_documento}}">{{$user->tipo_documento}}</option>
                                            <option value="Cedula de Ciudadania">Cedula de Ciudadanía</option>
                                            <option value="Cedula de Extranjeria">Cedula de Extranjeria</option>
                                            <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                        </select>
                                        @error('tipo_documento')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="documento">N° documento<span class="small text-danger">*</span></label>
                                        <input id="documento" type="text" class="form-control @error('documento') is-invalid @enderror" name="documento" value="{{$user->documento}}" required autofocus>
                                            @error('documento')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="nombre">Nombres<span class="small text-danger">*</span></label>
                                        <input type="text" id="nombre" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombres" value="{{$user->nombre}}">
                                        @error('nombre')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="apellido">Apellidos<span class="small text-danger">*</span></label>
                                        <input type="text" id="apellido" class="form-control @error('apellido') is-invalid @enderror" name="apellido" placeholder="Apellidos" value="{{$user->apellido}}">
                                        @error('apellido')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">Correo electronico<span class="small text-danger">*</span></label>
                                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="example@example.com" value="{{$user->email}}">
                                        @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="rol">Rol<span class="small text-danger">*</span></label>
                                        <select id="rol" name="rol" class="form-control @error('rol') is-invalid @enderror">
                                            <option value="">-- Seleccione un Rol --</option>
                                            @foreach($roles as $rol)
                                            <option value="{{$rol->id}}" {{$user->id_rol == $rol->id ? 'selected' : '' }}>{{$rol->nombre}}</option>
                                            @endforeach
                                        </select>
                                        @error('rol')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="icon text-white-50">Guardar cambios</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
