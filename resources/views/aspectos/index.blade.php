@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Aspectos</h2>
    </div>
    <div class="card shadow mb-4">
        @if (auth()->user()->rol->nombre == 'Decano')
            <div class="card-header py-3">
                <a href="{{ route('aspectos.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>
            </div>
        @else
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold">Aspectos Registrados</h6>
            </div>
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Codigo</th>
                        <th scole="col">Nombre</th>
                        <th scole="col">Característica</th>
                        <th scole="col">Progreso</th>
                        <th scole="col">Peso</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($aspectos as $aspecto)
                    <tr>
                        <td class="text-center">{{$aspecto->codigo}}</td>
                        <td>{{$aspecto->nombre}}</td>
                        <td>{{$aspecto->caracteristica->nombre}}</td>
                        <td>
                            @if ($aspecto->progreso == 0)
                                El aspecto aún no tiene progreso
                            @else
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: {{ $aspecto->progreso }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        {{ $aspecto->progreso }} %
                                    </div>
                                </div>
                            @endif

                        </td>
                        <td>{{ $aspecto->peso }}</td>
                        <td class="text-center">
                            @if ($aspecto->estado == "Activado")
                                <span class="badge badge-success">{{ $aspecto->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $aspecto->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('aspectos.show',['aspecto'=>$aspecto->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{ route('aspectos.edit', ['aspecto' => $aspecto->id]) }}" class="btn btn-warning mx-2 rounded">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('aspectos.estado', ['aspecto' => $aspecto->id]) }}" method="POST">
                                        @csrf
                                        @if($aspecto->estado=='Activado')
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

