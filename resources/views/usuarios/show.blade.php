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
                <div class="card-profile-image mt-3">
                    @if ($user->imagen)
                        <img src="/storage/{{ $user->imagen }}" alt="Imagen del Usuario" class="rounded-circle avatar avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;">
                    @else
                        <figure class="rounded-circle avatar avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;" data-initial="{{ Auth::user()->nombre[0] }}"></figure>
                    @endif
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h5 class="font-weight-bold">{{  $user->fullName }}</h5>
                                <p>{{  $user->rol->nombre }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-lg-1">
            <div class="card shadow mb-4">
                <div class="card-header py-3 my-1">
                    <h6 class="m-0 font-weight-bold text-primary">Miembro: {{ $user->fullname }}</h6>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4">Información del miembro</h6>
                    <div class="pl-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label font-weight-bold" for="tipo documento">Tipo de documento</label>
                                <p>{{ $user->tipo_documento }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group focused">
                                <label class="form-control-label font-weight-bold" for="documento">N° documento</label>
                                <p>{{ $user->documento }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold" for="email">Correo electronico</label>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold" for="estado">Estado</label>
                                @if ($user->estado == "Activado")
                                    <p>
                                        <span class="badge badge-success">{{ $user->estado }}</span>
                                    </p>
                                @else
                                    <p>
                                        <span class="badge badge-danger">{{ $user->estado }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col">
                            <a href="{{ route('usuarios.edit', ['user' => $user->id]) }}" class="text-primary">¿Desea editar este usuario?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
