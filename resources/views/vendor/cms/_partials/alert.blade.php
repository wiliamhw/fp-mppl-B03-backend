
@if (session()->has('alertMessage'))
    <div class="alert alert-custom alert-{{ session('alertType')  }} fade show mb-12" role="alert">
        <div class="alert-icon">
            @if (session('alertType') === 'danger')
            <i class="fa fa-exclamation-circle"></i>
            @else
            <i class="fa fa-info-circle"></i>
            @endif
        </div>
        <div class="alert-text">{{ session('alertMessage') }}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif

@if ($errors->count() > 0)
    <div class="alert alert-custom alert-danger fade show mb-12" role="alert">
        <div class="alert-icon"><i class="fa fa-exclamation-circle"></i></div>
        <div class="alert-text">Oops, there are some errors</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif
