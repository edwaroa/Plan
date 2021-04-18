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
                            <i class="far fa-clock"></i> {{ $plan->fecha_inicio }}
                        </div>
                        <div class="col-lg-6">
                            <h4>Fecha Final</h4>
                            <i class="fas fa-stopwatch"></i> {{ $plan->fecha_final }}
                        </div>
                        <div class="col-lg-12 mt-3 p-0">
                            <p class="text-lg text-primary font-weight-bold mb-0">Faltan: {{ $dias_fechas }} días</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">
            <div class="card shadow mb-4 p-3">
                <div class="mx-auto text-center col-md-10">
                    <h3 class="p-0 mb-5">{{ $plan->nombre }}</h3>
                </div>
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="mb-3">
                            <h3 class="text-primary mb-2">Descripción</h3>
                            <p>{{ $plan->descripcion }}</p>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-primary mb-2">Objetivo General</h3>
                            <p>{{ $plan->objetivo_general }}</p>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-primary mb-2">Objetivos Especificos</h3>
                            <p>{{ $plan->objetivos_especificos }}</p>
                        </div>
                    </div>
                    <div class="col-md-10 mx-auto">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-primary mb-2">Programa</h3>
                                <p>{{ $plan->programa->nombre }}</p>
                            </div>
                            <div class="col-md-6 float-right">
                                <h3 class="text-primary mb-2">Progreso</h3>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: {{ $plan->progreso }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        {{ $plan->progreso }} %
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mx-auto col-md-10">
                        <div class="row my-3">
                            <div class="col">
                                <a href="{{ route('planes.edit', ['plan' => $plan->id]) }}" class="text-primary">¿Desea editar esta plan?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>|
        </div>
    </div>

@endsection
