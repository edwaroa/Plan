@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <a href="javascript:history.back()" class="btn btn-outline-warning px-3 mx-2 my-2"><i class="fas fa-arrow-circle-left"></i></a>
    <div class="col-lg-12 order-lg-1">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Crear Miembro') }}</h6>
            </div>
            @if(session('estado'))
            <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
                {{session('estado')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.store') }}" autocomplete="off" novalidate enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <h6 class="heading-small text-muted mb-4">Información del miembro</h6>

                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="tipo_documento" class="form-control-label">{{ __('Tipo de documento') }}</label>
                                        <select id="tipo_documento" name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror" value="{{ old('tipo_documento')}}" autofocus>
                                            <option value="" selected disabled>-- Tipo de documento --</option>
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
                                    <label for="documento" class="form-control-label">{{ __('N° documento') }}</label>
                                    <input id="documento" type="text" class="form-control @error('documento') is-invalid @enderror" name="documento" value="{{ old('documento')}}" required placeholder="N° documento" autofocus>
                                    @error('documento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="nombre">Nombres<span class="small text-danger">*</span></label>
                                    <input type="text" id="nombre" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombres" value="{{ old('nombre') }}">
                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="apellido">Apellidos</label>
                                    <input type="text" id="apellido" class="form-control @error('apellido') is-invalid @enderror" name="apellido" placeholder="Apellidos" value="{{ old('apellido') }}">
                                    @error('apellido')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="email">Correo electronico<span class="small text-danger">*</span></label>
                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="ejemplo@udi.edu.co" value="{{ old('email')}}">
                                    @error('email')
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
                                    <label class="form-control-label" for="contraseña">Contraseña</label>
                                    <input type="password" id="contraseña" class="form-control @error('contraseña') is-invalid @enderror" name="contraseña" placeholder="Contraseña">
                                    @error('contraseña')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="confirmar_contraseña">Confirmar Contraseña</label>
                                    <input type="password" id="confirmar_contraseña" class="form-control @error('confirmar_contraseña') is-invalid @enderror" name="confirmar_contraseña" placeholder="Confirmar contraseña">
                                    @error('confirmar_contraseña')
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
                                    <label for="telefono" class="form-control-label">{{ __('Telefono / Celular') }}</label>
                                    <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono')}}" required placeholder="Numero de telefono o celular" autofocus>
                                    @error('telefono')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="genero" class="form-control-label d-block">{{ __('Genero') }}</label>

                                    <div class="@error('genero')
                                        is-invalid border border-danger rounded p-1
                                    @enderror">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero1" value="Masculino">
                                            <label class="form-check-label" for="genero1">Masculino</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero2" value="Femenino">
                                            <label class="form-check-label" for="genero2">Femenino</label>
                                        </div>
                                    </div>

                                    @error('genero')
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
                                    <label for="rol" class="form-control-label">{{ __('Rol') }}</label>
                                    <select id="rol" name="rol" class="form-control @error('rol') is-invalid @enderror">
                                    <option value="" selected disabled>-- Seleccione un Rol --</option>
                                        @foreach($roles as $rol)
                                            <option value="{{$rol->id}}" {{old('rol') == $rol->id ? 'selected' : '' }}>{{$rol->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @error('rol')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="imagen" class="form-control-label">{{ __('Imagen') }}</label>
                                    <input type="file" id="imagen" class="form-control @error('imagen') is-invalid @enderror" name="imagen">

                                    @error('imagen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                                <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="icon text-white-50">Crear Miembro</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
