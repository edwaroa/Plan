@extends('layouts.auth')

@section('main-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Verificar su Correo electronico') }}</h1>
                                </div>

                                @if (session('resent'))
                                    <div class="alert alert-success border-left-success" role="alert">
                                        {{ __('Un nuevo link ha sido enviado a su correo electronico') }}
                                    </div>
                                @endif

                                {{ __('Antes de proceder, por favor revise su correo electronico para un link de verificación.') }}
                                {{ __('Si no recibió un correo') }}, <a href="{{ route('verification.resend') }}">{{ __('click aqui para solicitar otro') }}</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
