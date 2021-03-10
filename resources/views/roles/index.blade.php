@extends('layouts.admin')

@section('main-content')

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Roles</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Roles Registrados</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Codigo</th>
                        <th scole="col">Nombre</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Ver</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($roles as $rol)
                    <tr>
                        <td>{{$rol->id}}</td>
                        <td>{{$rol->nombre}}</td>
                        <td>{{$rol->estado}}</td>
                        <td>
                            <a href="{{route('roles.show',['rol'=>$rol->id])}}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">Ver</span></a>
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
