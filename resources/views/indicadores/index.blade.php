@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Indicadores</h2>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @if (auth()->user()->rol->nombre == 'Decano')
                <a href="{{ route('indicadores.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>

                <a href="{{ route('indicadores.exportar') }}" class="m-0 btn btn-outline-danger inline-block float-right" target="__blank">Reporte pdf <i class="fas fa-file-pdf"></i></a>
            @else
                <h6 class="text-primary font-weight-bold d-inline-block">Indicadores Registradas</h6>
            @endif
        </div>

        <div class="card-body">

            @include('alertas.estado')

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Codigo</th>
                        <th scole="col">Nombre</th>
                        <th scole="col">Aspecto</th>
                        <th scole="col">Progreso</th>
                        <th scole="col">Peso</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($indicadores as $indicador)
                    <tr>
                        <td class="text-center">{{$indicador->codigo}}</td>
                        <td>{{$indicador->nombre}}</td>
                        <td>{{$indicador->aspecto->nombre}}</td>
                        <td>
                            @if ($indicador->progreso == 0)
                                El indicador aún no tiene progreso
                            @else
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: {{ $indicador->progreso }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="badge badge-info d-inline-block mx-auto">{{ $indicador->progreso }} %</span>
                            @endif

                        </td>
                        <td>{{ $indicador->peso }}</td>
                        <td class="text-center">
                            @if ($indicador->estado == "Activado")
                                <span class="badge badge-success">{{ $indicador->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $indicador->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('indicadores.show',['indicador'=>$indicador->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{ route('indicadores.edit', ['indicador' => $indicador->id]) }}" class="btn btn-warning mx-2 rounded">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('indicadores.estado', ['indicador' => $indicador->id]) }}" method="POST">
                                        @csrf
                                        @if($indicador->estado=='Activado')
                                        <button type="submit" class="btn btn-danger icon text-white-50"><i class="fas fa-trash"></i></button>
                                        @else
                                        <button type="submit" class="btn btn-success icon text-white-50"><i class="fas fa-check"></i></button>
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

