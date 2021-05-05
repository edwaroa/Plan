@if (session('estado'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('estado') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif



