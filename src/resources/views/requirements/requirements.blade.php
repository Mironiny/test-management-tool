@extends('layouts.mainlayout') @section('content')

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container">

        <div class="col-xs-12" style="height:85px;"></div>

        <!-- ... Your content goes here ... -->

        {{-- <div class="row">
            <div class="col-lg-8">
                <ul>
                    @foreach ($requirements as $requirement)
                        <li>{{ $requirement->Name }}</li>
                    @endforeach
                </ul>

            </div>
        </div> --}}

        <div class="row">
            {{-- <div class="dataTable_wrapper"> --}}
                                    <table class="table table-striped table-bordered table-hover" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{ $id = 1 }}
                                            @foreach ($requirements as $requirement)
                                                <tr>
                                                    <td>{{ $id++ }}</td>
                                                    <td><a href="#">{{ $requirement->Name }}</a></td>
                                                    <td>Not covered</td>
                                                </tr>

                                            @endforeach

                                        </table>
                                    </div>
                                {{-- </div> --}}


    </div>
</div>




@endsection
