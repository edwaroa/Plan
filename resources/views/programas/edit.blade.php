@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css" integrity="sha512-CWdvnJD7uGtuypLLe5rLU3eUAkbzBR3Bm1SFPEaRfvXXI2v2H5Y0057EMTzNuGGRIznt8+128QIDQ8RqmHbAdg==" crossorigin="anonymous" />
@endsection

@section('main-content')
<div class="container-fluid">
    <a href="javascript:history.back()" class="btn btn-outline-warning px-3 mx-2 my-2"><i class="fas fa-arrow-circle-left"></i></a>
    <div class="col-lg-12 order-lg-1">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Editar Programa') }}: {{ $programa->nombre }}</h6>
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
                <form method="POST" action="{{ route('programas.update', ['programa' => $programa->id]) }}" autocomplete="off" novalidate enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('PUT')

                    <h6 class="heading-small text-muted mb-4">Información del programa</h6>

                    <div class="pl-lg-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label for="nombre" class="form-control-label">{{ __('Nombre del Programa') }}</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre" value="{{ $programa->nombre }}" placeholder="Nombre del Programa">

                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label for="descripcion" class="form-control-label">{{ __('Descripción') }}</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control area @error('descripcion') is-invalid @enderror" placeholder="Descripción del Programa">{{ $programa->descripcion }}</textarea>


                                    @error('descripcion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label for="id_facultad" class="form-control-label">{{ __('Facultad') }}</label>
                                    <select name="id_facultad" id="id_facultad" class="form-control @error('id_facultad') is-invalid @enderror">
                                        <option value="" selected disabled>-- Seleccione una universidad --</option>
                                        @foreach ($facultades as $facultad)
                                            <option value="{{ $facultad->id }}" {{ $programa->facultad->id == $facultad->id ? 'selected' : '' }}>
                                                {{ $facultad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('id_facultad')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                                <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="icon text-white-50">Guardar Cambios</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js" integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ==" crossorigin="anonymous"></script>
@endsection
