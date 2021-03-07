@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Manuales</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Manuales de Usuario</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-light">
                    <tr>
                    <th scole="col">Nombre</th>
                    <th scole="col">Ver</th>
                    <th scole="col">Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>Manual de Usuario - Plan de Mejoramiento.pdf</td>
                    @php
                     $nombre="Manual de Usuario - Plan de Mejoramiento.pdf";
                    @endphp
                    <td>
                        <a href="/PlandeMejoramiento/storage/app/public/manuales/Manual de Usuario - Plan de Mejoramiento.pdf" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">Ver</a>
                    </td>
                    <td>
                        <a href="{{route('manuales.descargar',$nombre)}}" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">Descargar</span></a>
                    </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection
