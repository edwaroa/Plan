@extends('layouts.admin')

@section('main-content')

<div>
    <div>
        <h2>Administrar Corporacion</h2>
    </div>
    <div class="card shadow mb-4">

        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <img src="{{ asset('img/universidad.png') }}" alt="Universidad" style="width: 150px" class="mx-auto">
                        <div class="card-body text-center">
                          <h3 class="card-title text-center">Universidad</h3>
                          <p class="card-text">En este modulo podra administrar la universidad que se registre en la base de datos.</p>
                          <a href="{{ route('universidades.index') }}" class="btn btn-success">Ingresar</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <img src="{{ asset('img/facultad.png') }}" alt="Universidad" style="width: 150px" class="mx-auto">
                        <div class="card-body text-center">
                          <h3 class="card-title text-center">Facultades</h3>
                          <p class="card-text">En este modulo podra administrar las facultades que se registraron en la base de datos.</p>
                          <a href="#" class="btn btn-success">Ingresar</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <img src="{{ asset('img/programa.png') }}" alt="Universidad" style="width: 150px" class="mx-auto">
                        <div class="card-body text-center">
                          <h3 class="card-title text-center">Programas</h3>
                          <p class="card-text">En este modulo podra administrar los programas que se registraron en la base de datos.</p>
                          <a href="#" class="btn btn-success">Ingresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

