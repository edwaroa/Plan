@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <a href="javascript:history.back()" class="btn btn-outline-warning px-3 mx-1 my-2"><i class="fas fa-arrow-circle-left"></i></a>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4 p-3">
        <h2 class="mx-auto">{{ $programa->nombre }}</h2>
        <div class="p-2 position-absolute {{ $programa->estado == 'Activado' ? 'bg-success' : 'bg-danger' }} rounded justify-content-center" style="right: 0; top: 2px; margin: 10px 10px 0 0;">
            <p class="p-0 m-0 text-white">{{ $programa->estado }}</p>
        </div>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="mb-3">
                    <h3 class="text-primary mb-2">Descripción</h3>
                    <p>{{ $programa->descripcion }}</p>
                </div>
                <div class="mb-3">
                    <h3 class="text-primary mb-2">Facultad</h3>
                    <p><a class="text-warning" href="{{ route('facultades.show', ['facultad' => $programa->facultad->id]) }}">{{ $programa->facultad->nombre }}</a></p>
                </div>
                <div class="row my-3">
                    <div class="col">
                        <a href="{{ route('programas.edit', ['programa' => $programa->id]) }}" class="text-primary">¿Desea editar esta programa?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
