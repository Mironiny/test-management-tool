@extends('layouts.mainLayout')

@section('title')
    Test library
@endsection

@section('sidemenu')
    @include('library.librarySidemenu')
@endsection

@section('content')
<div class="col-xs-12" style="height:40px;"></div>
<span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
<div class="container">

    @include('layouts.status')

    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            <a href="{{ url('library/testcase/create')}}" class="btn btn-primary btn-block" role="button">
                <span class="glyphicon glyphicon-plus"></span> New test case
            </a>
        </div>
    </div>
    <div class="col-md-12" style="height:20px;"></div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Test case name</th>
                        <th>Test Suite</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($testCases))
                    <?php $id = 1; ?>
                        @foreach ($testCases as $testCase)
                            <tr>
                                <td>{{ $id }}</td>
                                <td><a href="{{ url("library/testcase/detail/$testCase->TestCase_id")}}">{{ $testCase->Name }}</a></td>
                                <td>{{ App\TestSuite::find($testCase->TestSuite_id)->Name }}</td>
                            </tr>
                            <?php $id++; ?>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

@endsection
