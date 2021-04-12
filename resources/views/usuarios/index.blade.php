@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Miembros</h2>
    </div>
    <div class="card shadow mb-4">
        @if (auth()->user()->rol->nombre == 'Decano')
            <div class="card-header py-3">
                <a href="{{ route('usuarios.create') }}" class="m-0 btn btn-outline-success inline-block">Agregar <i class="fas fa-plus"></i></a>
            </div>
        @else
            <div class="card-header py-3">
                <h6 class="text-primary font-weight-bold">Miembros Registrados</h6>
            </div>
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Documento</th>
                        <th scole="col">Nombre Completo</th>
                        <th scole="col">Correo electronico</th>
                        <th scole="col">Rol</th>
                        <th scole="col">Estado</th>
                        <th scole="col">Imagen</th>
                        <th scole="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{$usuario->documento}}</td>
                        <td>{{$usuario->nombre}} {{ $usuario->apellido }}</td>
                        <td>{{$usuario->email}}</td>
                        <td>{{$usuario->rol->nombre}}</td>
                        <td class="text-center">
                            @if ($usuario->estado == "Activado")
                                <span class="badge badge-success">{{ $usuario->estado }}</span>
                            @else
                                <span class="badge badge-danger">{{ $usuario->estado }}</span>
                            @endif

                        </td>
                        <td width="100" style="padding: 2px">
                            <img src="/storage/{{ $usuario->imagen }}" width="100%" alt="Imagen del usuario">
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('usuarios.show',['user'=>$usuario->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{route('usuarios.edit',['user'=>$usuario->id])}}" class="btn btn-warning mx-2 rounded">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{route('usuarios.estado',['user'=>$usuario->id])}}" method="POST">
                                        @csrf
                                        @if($usuario->estado=='Activado')
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
