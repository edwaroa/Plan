@extends('layouts.admin')

@section('main-content')

<div class="container-fluid">
    <div>
        <h2>Administrar Roles</h2>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Roles Registrados</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive w-sm">
                <table class="table table-bordered mx-auto" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Codigo</th>
                        <th scole="col">Nombre</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($roles as $rol)
                    <tr>
                        <td>{{$rol->id}}</td>
                        <td>{{$rol->nombre}}</td>
                        <td class="text-center">
                            @if ($rol->estado == "Activado")
                                <span class="badge badge-success">{{ $rol->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $rol->estado }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('roles.show',['rol'=>$rol->id])}}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50"><i class="fas fa-eye"></i></span></a>
                        </td>
                    </tr>
                     @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
