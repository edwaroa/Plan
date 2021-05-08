<div class="modal fade" id="evidenciaModal" tabindex="-1" role="dialog" aria-labelledby="evidenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evidenciaModalLabel">Agregar Evidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form method="POST" action="{{ route('evidencias.store') }}" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group focused">
                            <label for="descripcion" class="form-control-label">{{ __('Descripción') }}</label>
                            <textarea name="descripcion" id="descripcion" class="form-control area @error('descripcion') is-invalid @enderror" placeholder="Descripción de la evidencia" style="min-height: 150px">{{ old('descripcion') }}</textarea>

                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group focused">
                            <label for="url_documento" class="form-control-label">{{ __('Documento') }}</label>
                            <input type="file" id="url_documento" class="form-control @error('url_documento') is-invalid @enderror" name="url_documento">

                            @error('url_documento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <input type="hidden" name="actividad_id" value="{{ $actividad->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Subir</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="verEvidenciaModal" tabindex="-1" role="dialog" aria-labelledby="verEvidenciaModalLabel" aria-hidden="true" width="800px">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verEvidenciaModalLabel">Evidencias subidas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th scole="col">Descripción</th>
                                <th scole="col">Subida por</th>
                                <th scole="col">Fecha</th>
                                <th scole="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($evidencias as $evidencia)
                                <tr>
                                    <td scole="col">{{ $evidencia->descripcion }}</td>
                                    <td scole="col">{{ $evidencia->usuario->fullname }}</td>
                                    <td scole="col">{{ $evidencia->created_at }}</td>
                                    <td scole="col">
                                        <div class="btn-group mx-auto">
                                            <a href="{{ route('evidencias.descargar', ['id' => $evidencia->id]) }}" class="btn btn-success mx-2 rounded">
                                                <i class="fas fa-download"></i>
                                            </a>

                                            <form action="{{ route('evidencias.destroy', ['evidencia' => $evidencia->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash" aria-hidden="true">

                                                    </i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tramitarModal" tabindex="-1" role="dialog" aria-labelledby="tramitarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-" id="tramitarModalLabel">Calificar Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('actividades.avalar', ['actividad' => $actividad->id]) }}">
                @csrf
                <div class="modal-body">
                    <select class="form-control @error('estado') is-invalid @enderror" name="estado" id="estado">
                        <option selected disabled>----- Seleccione una opción -----</option>
                        <option value="Avalada">Avalar</option>
                        <option value="Rechazada">Rechazar</option>
                    </select>

                    @error('estado')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info text-white">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>

