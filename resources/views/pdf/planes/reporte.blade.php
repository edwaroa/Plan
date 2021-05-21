<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exportar PDF</title>

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">> --}}
    <style>
        td {
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            color: grey;
        }
    </style>

</head>
<body style="font-family: 'Arial', sans-serif;">

    <header>
        <table style="margin-bottom: 20px" width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">

            <tr>

                <td colspan="2" valign="middle" align="left" width="200px" style="padding: 4px; font-weight: bold">
                    Universidad: <span style="font-weight: normal">{{ $universidad[0]->nombre }}</span></td>
                </td>

                <td colspan="2" style="text-align: center; font-weight: bold">Reporte de planes, proyectos, factores, caracteristicas, aspectos, indicadores y actividades</td>
            </tr>

            <tr>

                <td colspan="2" style="text-align: center; font-weight: bold">Fecha: <span style="font-weight: normal">{{ $fecha }}</span></td>
                <td colspan="2" style="text-align: center; font-weight: bold">Codigo: <span style="font-weight: normal">{{ $codigo }}</span></td>

            </tr>

        </table>
    </header>

    <div>
        @foreach($planes as $plan)
            <table width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
                <tr>
                    <th colspan="6" style="padding: 5px 0 5px 0">{{ $planes[0]->nombre }}</th>
                </tr>
                <tr>
                    <th>Programa</th>
                    <th>descripción</th>
                    <th>Objetivo General</th>
                    <th>Objetivos Especificos</th>
                    <th>Progreso</th>
                    <th>Estado</th>
                </tr>
                <tbody>
                    <tr>
                        <td>{{$plan->programa->nombre}}</td>
                        <td>{{ $plan->descripcion }}</td>
                        <td>{{ $plan->objetivo_general }}</td>
                        <td>{{ $plan->objetivos_especificos }}</td>
                        <td>{{ $plan->progreso }}%</td>
                        <td>
                            {{ $plan->estado }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    </div>

    <div>
        <table style="margin: 30px auto" width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
            <tr>
                <th colspan="7" style="padding: 5px 0 5px 0">Proyectos Asignados</th>
            </tr>
            <tr>
                <th>Proyecto</th>
                <th>descripción</th>
                <th>Objetivo General</th>
                <th>Objetivos Especificos</th>
                <th>Progreso</th>
                <th>Peso</th>
                <th>Estado</th>
            </tr>
            <tbody>
                @foreach ($planes[0]->proyectos as $proyecto)
                <tr>
                    <td>{{ $proyecto->nombre }}</td>
                    <td>{{ $proyecto->descripcion }}</td>
                    <td>{{ $proyecto->objetivo_general }}</td>
                    <td>{{ $proyecto->objetivos_especificos }}</td>
                    <td>{{ $proyecto->progreso }}%</td>
                    <td>{{ $proyecto->peso }}</td>
                    <td>
                        {{ $proyecto->estado }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div>
        <table style="margin: 30px auto" width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
            <tr>
                <th colspan="7" style="padding: 5px 0 5px 0">Factores</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Factor</th>
                <th>Proyecto</th>
                <th>descripción</th>
                <th>Progreso</th>
                <th>Peso</th>
                <th>Estado</th>
            </tr>
            <tbody>
                @foreach ($planes[0]->proyectos as $proyecto)
                    @foreach ($proyecto->factores as $factor)
                    <tr>
                        <td>{{ $factor->codigo }}</td>
                        <td>{{ $factor->nombre }}</td>
                        <td>{{ $factor->proyecto->nombre }}</td>
                        <td>{{ $factor->descripcion }}</td>
                        <td>{{ $factor->progreso }}%</td>
                        <td>{{ $factor->peso }}</td>
                        <td>
                            {{ $factor->estado }}
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>

    <div>
        <table style="margin: 30px auto" width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
            <tr>
                <th colspan="7" style="padding: 5px 0 5px 0">Características</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Factor</th>
                <th>Proyecto</th>
                <th>descripción</th>
                <th>Progreso</th>
                <th>Peso</th>
                <th>Estado</th>
            </tr>
            <tbody>
                @foreach ($planes[0]->proyectos as $proyecto)
                    @foreach ($proyecto->factores as $factor)
                        @foreach ($factor->caracteristicas as $caracteristica)
                            <tr>
                                <td>{{ $caracteristica->codigo }}</td>
                                <td>{{ $caracteristica->nombre }}</td>
                                <td>{{ $caracteristica->factor->nombre }}</td>
                                <td>{{ $caracteristica->descripcion }}</td>
                                <td>{{ $caracteristica->progreso }}%</td>
                                <td>{{ $caracteristica->peso }}</td>
                                <td>
                                    {{ $caracteristica->estado }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>

    <div>
        <table style="margin: 30px auto" width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
            <tr>
                <th colspan="7" style="padding: 5px 0 5px 0">Aspectos</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Característica</th>
                <th>Proyecto</th>
                <th>descripción</th>
                <th>Progreso</th>
                <th>Peso</th>
                <th>Estado</th>
            </tr>
            <tbody>
                @foreach ($planes[0]->proyectos as $proyecto)
                    @foreach ($proyecto->factores as $factor)
                        @foreach ($factor->caracteristicas as $caracteristica)
                            @foreach ($caracteristica->aspectos as $aspecto)
                                <tr>
                                    <td>{{ $aspecto->codigo }}</td>
                                    <td>{{ $aspecto->nombre }}</td>
                                    <td>{{ $aspecto->caracteristica->nombre }}</td>
                                    <td>{{ $aspecto->descripcion }}</td>
                                    <td>{{ $aspecto->progreso }}%</td>
                                    <td>{{ $aspecto->peso }}</td>
                                    <td>
                                        {{ $aspecto->estado }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>

    <div>
        <table style="margin: 30px auto" width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
            <tr>
                <th colspan="7" style="padding: 5px 0 5px 0">Indicadores</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Característica</th>
                <th>Proyecto</th>
                <th>descripción</th>
                <th>Progreso</th>
                <th>Peso</th>
                <th>Estado</th>
            </tr>
            <tbody>
                @foreach ($planes[0]->proyectos as $proyecto)
                    @foreach ($proyecto->factores as $factor)
                        @foreach ($factor->caracteristicas as $caracteristica)
                            @foreach ($caracteristica->aspectos as $aspecto)
                                @foreach ($aspecto->indicadores as $indicador)
                                    <tr>
                                        <td>{{ $indicador->codigo }}</td>
                                        <td>{{ $indicador->nombre }}</td>
                                        <td>{{ $indicador->aspecto->nombre }}</td>
                                        <td>{{ $indicador->descripcion }}</td>
                                        <td>{{ $indicador->progreso }}%</td>
                                        <td>{{ $indicador->peso }}</td>
                                        <td>
                                            {{ $indicador->estado }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>

    <div>
        <table style="margin: 30px auto" width="100%" border="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
            <tr>
                <th colspan="7" style="padding: 5px 0 5px 0">Actividades</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Indicador</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Peso</th>
                <th>Responsables</th>
                <th>Estado</th>
            </tr>
            <tbody>
                @foreach ($planes[0]->proyectos as $proyecto)
                    @foreach ($proyecto->factores as $factor)
                        @foreach ($factor->caracteristicas as $caracteristica)
                            @foreach ($caracteristica->aspectos as $aspecto)
                                @foreach ($aspecto->indicadores as $indicador)
                                    @foreach ($indicador->actividades as $actividad)
                                    <tr>
                                        <td>{{$actividad->nombre}}</td>
                                        <td>{{$actividad->indicador->nombre}}</td>
                                        <td>{{ $actividad->fecha_inicio }}</td>
                                        <td>{{ $actividad->tiempo_entrega }}</td>
                                        <td>{{ $actividad->peso }}</td>
                                        <td>
                                            @foreach ($actividad->users as $usuarios)
                                                <li style="list-style: none">{{ $usuarios->fullname }}</li>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $actividad->estado }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>


    <footer>
        {{ $universidad[0]->nombre }} - 2021
    </footer>




    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> --}}
</body>
</html>
