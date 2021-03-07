@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
<h1 class="h3 mb-2 text-gray-800">Roles</h1>
    <div class="col-lg-10 order-lg-1">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="m-0 font-weight-bold text-primary">{{ __('Editar Rol') }}</div>
            </div>  
            <div class="card-body">
                <form method="POST" action="{{ route('roles.update', ['rol'=>$rol->id])}}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <h6 class="heading-small text-muted mb-4">Información del Rol</h6>
                    <div class="pl-lg-4">
                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>
                            <div class="col-md-5">
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{$rol->nombre}}"autofocus>
                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-md-right">{{ __('Estado') }}</label>
                            <div class="col-md-5">
                                <select id="estado" name="estado" class="form-control @error('estado') is-invalid @enderror">
                                    <option value="{{$rol->estado}}">{{$rol->estado}}</option>
                                    <option value="Activado">Activado</option>
                                    <option value="Desactivado">Desactivado</option>
                                </select>
                                @error('estado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3 text-center">
                                <button type="submit" class="btn btn-primary">
                                    <span class="icon text-white-50">{{ __('Actualizar') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
