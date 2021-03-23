@extends('layouts.admin')

@section('main-content')

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Miembros</h1>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Miembros Registrados</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                        <th scole="col">Tipo de documento</th>
                        <th scole="col">Documento</th>
                        <th scole="col">Nombres</th>
                        <th scole="col">Apellidos</th>
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
                        <td>{{$usuario->tipo_documento}}</td>
                        <td>{{$usuario->documento}}</td>
                        <td>{{$usuario->nombre}}</td>
                        <td>{{$usuario->apellido}}</td>
                        <td>{{$usuario->email}}</td>
                        <td>{{$usuario->rol->nombre}}</td>
                        <td class="text-center">
                            @if ($usuario->estado == "Activado")
                                <span class="btn btn-success btn-sm">{{ $usuario->estado }}</span>
                            @else
                                <span class="btn btn-danger btn-sm">{{ $usuario->estado }}</span>
                            @endif

                        </td>
                        <td>
                            <img src="/storage/{{ $usuario->imagen }}" width="40px" alt="Imagen del usuario">
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('usuarios.show',['user'=>$usuario->id])}}" class="btn btn-primary rounded">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->rol->nombre == "Decano")
                                    <a href="{{route('usuarios.edit',['user'=>$usuario->id])}}" class="btn btn-info mx-2 rounded">
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
