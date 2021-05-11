@extends('layouts.admin')

@section('main-content')


    <!-- Page Heading -->
    <a href="javascript:history.back()" class="btn btn-outline-warning px-3 mx-1 my-2"><i class="fas fa-arrow-circle-left"></i></a>
    @include('alertas.success')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-center">
                    <div class="d-inline-block text-white text-lg mx-1">
                        <i class="fas fa-calendar-alt text-lg"></i> Fechas
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-lg-6">
                            <h4>Fecha Inicio</h4>
                            <i class="far fa-clock"></i> {{ $actividad->fecha_inicio }}
                        </div>
                        <div class="col-lg-6">
                            <h4>Fecha Entrega</h4>
                            <i class="fas fa-stopwatch"></i> {{ $actividad->tiempo_entrega }}
                        </div>
                        <div class="col-lg-12 mt-3 p-0">
                            <p class="text-lg text-primary font-weight-bold mb-0">Faltan: {{ $dias_fechas }} días</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header bg-info text-center">
                    <div class="d-inline-block text-white text-lg mx-1">
                        <i class="fas fa-cloud-upload-alt text-lg"></i> Evidencias
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <button type="button" class="btn btn-info text-white" data-toggle="modal" data-target="#evidenciaModal">
                            <i class="fas fa-upload"></i> Subir Evidencias
                        </button>

                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#verEvidenciaModal">
                            <i class="fas fa-file-word"></i> Ver Evidencias
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-center">
                    <div class="d-inline-block text-white text-lg mx-1">
                        <i class="fas fa-chart-line text-lg"></i> Calificación
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#calificacionModal">
                            <i class="fas fa-book"></i> Ver calificación
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-lg-1">
            <div class="card shadow mb-4 p-3">
                <div class="mx-auto text-center col-md-10">
                    <h3 class="p-0 mb-5 d-inline-block">{{ $actividad->nombre }}</h3>
                </div>
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="mb-3">
                            <h3 class="text-primary mb-2">Descripción</h3>
                            <p>{{ $actividad->descripcion }}</p>
                        </div>
                    </div>
                    <div class="col-md-10 mx-auto">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-primary mb-2">Indicador</h3>
                                <p>{{ $actividad->indicador->nombre }}</p>
                            </div>
                            <div class="col-md-6 float-right">
                                <h3 class="text-primary mb-2">Usuarios Encargados</h3>
                                <ul>
                                    @foreach ($actividad->users as $usuario)
                                        <li>{{ $usuario->nombre }} ({{ $usuario->rol->nombre }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 mx-auto">
                        <div class="mb-3">
                            <h3 class="text-primary mb-2">Peso de la actividad</h3>
                            <p class="rounded font-weight-bold border border-success d-inline-block p-2 bg-success text-white">{{ $actividad->peso }}</p>
                        </div>
                    </div>
                    <div class="mx-auto col-md-10">
                        <div class="row my-3">
                            <div class="col-md-8">
                                <a href="{{ route('actividades.edit', ['actividad' => $actividad->id]) }}" class="text-primary">¿Desea editar esta actividad?</a>
                            </div>
                            @if (Auth::user()->rol->nombre == "Decano")
                                <div class="col-md-4">
                                    <button class="btn btn-success d-inline-block float-right" data-toggle="modal" data-target="#tramitarModal">Tramitar</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modales.evidencias')

@endsection
