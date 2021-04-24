@if (session('status_estado') === 'si')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('tipo') }} Correctamente!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif (session('status_estado') === 'no')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        No se pudo activar correctamente, ya que el peso es mayor al total disponible!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
