@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Características</h2>
    </div>
    <div class="card shadow mb-4">
        @if (auth()->user()->rol->nombre == 'Decano')
            <div class="card-header py-3">
                <a href="{{ route('caracteristicas.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>
            </div>
        @else
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold">Características Registrados</h6>
            </div>
        @endif

        <div class="card-body">

            @include('alertas.estado')

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Codigo</th>
                        <th scole="col">Nombre</th>
                        <th scole="col">Factor</th>
                        <th scole="col">Progreso</th>
                        <th scole="col">Peso</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($caracteristicas as $caracteristica)
                    <tr>
                        <td class="text-center">{{$caracteristica->codigo}}</td>
                        <td>{{$caracteristica->nombre}}</td>
                        <td>{{$caracteristica->factor->nombre}}</td>
                        <td>
                            @if ($caracteristica->progreso == 0)
                                El caracteristica aún no tiene progreso
                            @else
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: {{ $caracteristica->progreso }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        {{ $caracteristica->progreso }} %
                                    </div>
                                </div>
                            @endif

                        </td>
                        <td>{{ $caracteristica->peso }}</td>
                        <td class="text-center">
                            @if ($caracteristica->estado == "Activado")
                                <span class="badge badge-success">{{ $caracteristica->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $caracteristica->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('caracteristicas.show',['caracteristica'=>$caracteristica->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{ route('caracteristicas.edit', ['caracteristica' => $caracteristica->id]) }}" class="btn btn-warning mx-2 rounded">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('caracteristicas.estado', ['caracteristica' => $caracteristica->id]) }}" method="POST">
                                        @csrf
                                        @if($caracteristica->estado=='Activado')
                                        <button type="submit" class="btn btn-danger icon text-white-50"><i class="fas fa-user-times"></i></button>
                                        @else
                                        <button type="submit" class="btn btn-success icon text-white-50"><i class="fas fa-user-check"></i></button>
                                        @endif
                                    </form>
                                @endif

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

