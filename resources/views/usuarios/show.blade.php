@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Miembros</h1>
    <div class="col-lg-12 order-lg-1">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="m-0 font-weight-bold text-primary">{{ __('Información general') }}</div>
            </div>
            @if(session('estado'))
                <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
                    {{session('estado')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <h6 class="heading-small text-muted mb-4">Información del miembro</h6>

                        <div class="pl-lg-4">
                        <h1 class="text-center">{{$user->nombre}}</h1>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label for="tipo documento" class="form-control-label">{{ __('Tipo de documento') }}</label>
                                        <input id="tipo documento" type="text" class="form-control" name="tipo documento" value="{{$user->tipo_documento}}" readonly="readonly" autofocus>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label for="documento" class="form-control-label">{{ __('N° documento') }}</label>
                                        <input id="documento" type="text" class="form-control" name="documento" value="{{$user->documento}}" readonly="readonly" autofocus>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="nombre">Nombres</label>
                                        <input id="nombre" type="text" class="form-control" name="nombre" value="{{$user->nombre}}" readonly="readonly" autofocus>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="apellido">Apellidos</label>
                                        <input id="apellido" type="text" class="form-control" name="apellido" value="{{$user->apellido}}" readonly="readonly" autofocus>
                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">Correo electronico</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label for="rol" class="form-control-label">{{ __('Rol') }}</label>
                                        <input id="rol" type="text" class="form-control" name="rol" value="{{$user->rol->nombre}}" readonly="readonly" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->rol->nombre=="Decano")
                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-5">
                            <a href="{{route('usuarios.edit',['user'=>$user->id])}}" class="btn btn-primary">
                                <span class="icon text-white-50">Editar</span></a>
                            </div>
                        </div>
                        @endif
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

