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
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Crear Actividad') }}</h6>
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
                <form method="POST" action="{{ route('actividades.store') }}" autocomplete="off" novalidate enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <h6 class="heading-small text-muted mb-4">Información de la actividad</h6>

                    <div class="pl-lg-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label for="nombre" class="form-control-label">{{ __('Nombre de la Actividad') }}</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Nombre de la actividad">

                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label for="descripcion" class="form-control-label">{{ __('Descripción') }}</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control area @error('descripcion') is-invalid @enderror" placeholder="Descripción de la actividad">{{ old('descripcion') }}</textarea>


                                    @error('descripcion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="id_indicador" class="form-control-label">{{ __('Indicador') }}</label>
                                    <select name="id_indicador" id="id_indicador" class="form-control @error('id_indicador') is-invalid @enderror">
                                        <option value="" selected disabled>-- Seleccione un Indicador --</option>
                                        @foreach ($indicadores as $indicador)
                                            <option value="{{ $indicador->id }}" {{ old('id_indicador') == $indicador->id ? 'selected' : '' }}>
                                                {{ $indicador->nombre }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('id_indicador')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="peso" class="form-control-label">{{ __('Peso: ') }} <span class="text-success" id="peso_total"></span></label>
                                    <input type="number" step=".1" class="form-control @error('peso') is-invalid @enderror" min="0" name="peso" id="peso" value="{{ old('peso') }}" placeholder="Peso de la actividad">

                                    @error('peso')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="fecha_inicio" class="form-control-label">{{ __('Fecha de inicio') }} <span class="text-success" id="fechap_inicio"></span></label>
                                    <input class="form-control @error('fecha_inicio') is-invalid @enderror" name="fecha_inicio" type="date" id="fecha_inicio" value="{{ old('fecha_inicio') }}">

                                    @error('fecha_inicio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label for="tiempo_entrega" class="form-control-label">{{ __('Fecha de entrega') }} <span class="text-success" id="tiempop_entrega"></span></label>
                                    <input class="form-control @error('tiempo_entrega') is-invalid @enderror" name="tiempo_entrega" type="date" id="tiempo_entrega" value="{{ old('tiempo_entrega') }}">

                                    @error('tiempo_entrega')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 mx-2">
                                <div class="form-group focused">
                                    <h3 class="text-center">Elegir usuarios encargados:</h1>
                                    @foreach ($usuarios as $usuario)
                                        <div class="form-check-inline mr-5">
                                            <input class="form-check-input text-lg" type="checkbox" id="usuario_{{ $usuario->id }}" name="usuarios[]" value="{{ $usuario->id }}" required

                                            @if (is_array(old('usuarios')) && in_array("$usuario->id", old('usuarios')))
                                                checked
                                            @endif

                                            >
                                            <label class="form-check-label h5" for="usuario_{{ $usuario->id }}">{{ $usuario->fullname }}</label>
                                        </div>
                                    @endforeach

                                    <div class="my-3">
                                        {{ $usuarios->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('usuarios')
                            <div class="alert alert-danger">
                                <li style="list-style: none">{{ $message }}</li>
                            </div>
                        @enderror

                                <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="icon text-white-50">Crear Actividad</span>
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
    <script src="{{ asset('js/actividades.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js" integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ==" crossorigin="anonymous"></script>
@endsection
