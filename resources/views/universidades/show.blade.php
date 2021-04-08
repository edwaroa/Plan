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
        <h2 class="mx-auto">{{ $universidad->nombre }}</h2>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="mb-3">
                    <h3 class="text-primary mb-2">Descripción</h3>
                    <p>{{ $universidad->descripcion }}</p>
                </div>
                <div class="mb-3">
                    <h3 class="text-primary mb-2">Misión</h3>
                    <p>{{ $universidad->mision }}</p>
                </div>
                <div class="mb-3">
                    <h3 class="text-primary mb-2">Visión</h3>
                    <p>{{ $universidad->vision }}</p>
                </div>
                <div class="row my-3">
                    <div class="col">
                        <a href="{{ route('universidades.edit', ['universidad' => $universidad->id]) }}" class="text-primary">¿Desea editar esta universidad?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
