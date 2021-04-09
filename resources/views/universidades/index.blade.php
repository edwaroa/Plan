@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Universidad</h2>
    </div>
    <div class="card shadow mb-4">
        @if ($contarUniversidades === 0 && auth()->user()->rol->nombre == 'Decano')
            <div class="card-header py-3">
                <a href="{{ route('universidades.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>
            </div>
        @else
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold">Universidad Registrada</h6>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Nombre</th>
                        <th scole="col">Descripción</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($universidades as $universidad)
                    <tr>
                        <td>{{$universidad->nombre}}</td>
                        <td>{{$universidad->descripcion}}</td>
                        <td class="text-center">
                            @if ($universidad->estado == "Activado")
                                <span class="badge badge-success">{{ $universidad->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $universidad->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('universidades.show',['universidad'=>$universidad->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{ route('universidades.edit', ['universidad' => $universidad->id]) }}" class="btn btn-warning mx-2 rounded">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('universidades.estado', ['universidad' => $universidad->id]) }}" method="POST">
                                        @csrf
                                        @if($universidad->estado=='Activado')
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

