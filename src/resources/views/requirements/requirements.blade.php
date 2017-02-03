@extends('layouts.mainlayout') @section('content')
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">

        <div class="col-xs-12" style="height:85px;"></div>

        <!-- ... Your content goes here ... -->

        <div class="row">
            <div class="col-lg-8">
                <ul>
                    @foreach ($requirements as $requirement)
                        <li>{{ $requirement->Name }}</li>
                    @endforeach
                </ul>
            
            </div>
        </div>

    </div>
</div>


@endsection
