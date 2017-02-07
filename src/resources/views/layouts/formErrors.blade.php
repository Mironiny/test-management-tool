{{--    --}}
{{-- Shows validation error. --}}
{{--    --}}

@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <strong>@lang('layout.failure')!</strong> {{ $error }}
                </div>
            </div>
        </div>
    @endforeach
@endif
