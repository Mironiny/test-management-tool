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

    @if (Request::is('library/filter/*'))

        <form class="form-horizontal" action="{{ url('library/testsuite/create')}}" method="POST">
            {{ csrf_field() }}
            {{-- <div class="form-group">
                <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Test suite name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name='name' maxlength="45" disabled="disabled" value="{{ $selectedSuite->Name }}" required autofocus>
                </div>
            </div> --}}

            <div class="form-group">
                <label class="control-label col-sm-2" for="test">Test suite description:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="description" name='description' placeholder="Enter test suite description">{{ $selectedSuite->TestSuiteDocumentation }}</textarea>
                    <div class="pull-right">
                        <div id="feedback2"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="test">Test suite goals:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="goals" name='goals' placeholder="Enter test suite goals">{{ $selectedSuite->TestSuiteGoals }}</textarea>
                    <div class="pull-right">
                        <div id="feedback1"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>

    @endif

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
