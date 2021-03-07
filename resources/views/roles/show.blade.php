@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Roles</h1>
    <div class="col-lg-10 order-lg-1">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="m-0 font-weight-bold text-primary">{{ __('Información del Rol') }}</div>
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
                <form method="POST" action="" enctype="multipart/form-data" novalidate>
                    @csrf
                    <h1 class="text-center">{{$rol->name}}</h1>
                    <div class="form-group row">
                        <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>
                        <div class="col-md-5">
                            <input id="nombre" type="text" class="form-control" name="nombre" value="{{$rol->nombre}}" readonly="readonly" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="estado" class="col-md-4 col-form-label text-md-right">{{ __('Estado') }}</label>
                        <div class="col-md-5">
                            <input id="estado" type="estado" class="form-control" name="estado" value="{{$rol->estado}}" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-3 text-center">
                        <a href="{{route('roles.edit',['rol'=>$rol->id])}}" class="btn btn-primary">
                            <span class="icon text-white-50">Editar</span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Usuarios</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Usuarios asignados</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th scole="col">Tipo de documento</th>
                            <th scole="col">Documento</th>
                            <th scole="col">Nombres</th>
                            <th scole="col">Apellidos</th>
                            <th scole="col">Correo electronico</th>
                            <th scole="col">Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{$usuario->tipo_documento}}</td>
                            <td>{{$usuario->documento}}</td>
                            <td>{{$usuario->nombre}}</td>
                            <td>{{$usuario->apellido}}</td>
                            <td>{{$usuario->email}}</td>
                            <td>{{$usuario->rol->nombre}}</td>
                        </tr>
                         @endforeach
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
@endsection
