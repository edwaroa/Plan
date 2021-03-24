@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <div class="col-lg-10 order-lg-1 mx-auto">
        <a href="javascript:history.back()" class="btn btn-outline-warning px-3 mx-1 my-2"><i class="fas fa-arrow-circle-left"></i></a>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="m-0 font-weight-bold text-primary">{{ __('Información General') }}</div>
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
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold" for="nombre">Nombre</label>
                            <p>{{ $rol->nombre }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold" for="estado">Estado</label>
                            @if ($rol->estado == "Activado")
                                <p>
                                    <span class="badge badge-success">{{ $rol->estado }}</span>
                                </p>
                            @else
                                <p>
                                    <span class="badge badge-danger">{{ $rol->estado }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Miembros asignados</h6>
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
</div>
@endsection
