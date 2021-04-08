@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Facultades</h2>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('facultades.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Facultad</th>
                        <th scole="col">Universidad</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($facultades as $facultad)
                    <tr>
                        <td>{{$facultad->nombre}}</td>
                        <td>{{$facultad->universidad->nombre}}</td>
                        <td class="text-center">
                            @if ($facultad->estado == "Activado")
                                <span class="badge badge-success">{{ $facultad->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $facultad->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('facultades.show',['facultad'=>$facultad->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{ route('facultades.edit', ['facultad' => $facultad->id]) }}" class="btn btn-warning mx-2 rounded">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('facultades.estado', ['facultad' => $facultad->id]) }}" method="POST">
                                        @csrf
                                        @if($facultad->estado=='Activado')
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

