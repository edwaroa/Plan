@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Actividades</h2>
    </div>
    <div class="card shadow mb-4">
        @if (auth()->user()->rol->nombre == 'Decano')
            <div class="card-header py-3">
                <a href="{{ route('actividades.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>
            </div>
        @else
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold">Actividades Registrados</h6>
            </div>
        @endif

        <div class="card-body">

            @include('alertas.estado')

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Nombre</th>
                        <th scole="col">Indicador</th>
                        <th scole="col">Fecha de Inicio</th>
                        <th scole="col">Fecha de Entrega</th>
                        <th scole="col">Usuarios Encargados</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($actividades as $actividad)
                    <tr>
                        <td>{{$actividad->nombre}}</td>
                        <td>{{$actividad->indicador->nombre}}</td>
                        <td>{{ $actividad->fecha_inicio }}</td>
                        <td>{{ $actividad->tiempo_entrega }}</td>
                        <td>
                            @foreach ($actividad->users as $usuarios)
                                <li style="list-style: none">{{ $usuarios->fullname }}</li>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @if ($actividad->estado == "Activado")
                                <span class="badge badge-success">{{ $actividad->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $actividad->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('actividades.show',['actividad'=>$actividad->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('actividades.edit', ['actividad' => $actividad->id]) }}" class="btn btn-warning mx-2 rounded">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </div>
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

