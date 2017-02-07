{{--    --}}
{{-- Show status of the page - Succes or failure. Status depends on controller which gives a session variable --}}
{{--    --}}

@if (session('statusSuccess'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <strong>@lang('layout.success')!</strong> {{ session('statusSuccess') }}.
            </div>
        </div>
    </div>
@endif

@if (session('statusFailure'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <strong>@lang('layout.failure')!</strong> {{ session('statusFailure') }}.
            </div>
        </div>
    </div>
@endif
