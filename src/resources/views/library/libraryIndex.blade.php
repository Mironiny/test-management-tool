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
    @include('layouts.formErrors')

    @if (Request::is('library/filter/*'))

        <form class="form-horizontal" action="{{ url("library/testsuite/update/$selectedSuite->TestSuite_id")}}" method="POST">
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
                        <div id="feedback1"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="What test suite test?" class="glyphicon glyphicon-info-sign"></span> Test suite goals:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="goals" name='goals' placeholder="Enter test suite goals">{{ $selectedSuite->TestSuiteGoals }}</textarea>
                    <div class="pull-right">
                        <div id="feedback2"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6">
                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default" role="button">Delete test suite</button>
                </div>
            </div>

            <!--DIV for confirmation dialog-->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirmation</h4>
                        </div>
                        <div class="modal-body">
                            <p>Do you really want to delete test suite?</p>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ url("library/testsuite/terminate/$selectedSuite->TestSuite_id")}}" class="btn btn-default" role="button">Yes</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <a href="{{ url("library/testcase/create/$selectedSuite->TestSuite_id")}}" class="btn btn-primary btn-block" role="button">
                    <span class="glyphicon glyphicon-plus"></span> New test case
                </a>
            </div>
        </div>

    @else

        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <a href="{{ url('library/testcase/create')}}" class="btn btn-primary btn-block" role="button">
                    <span class="glyphicon glyphicon-plus"></span> New test case
                </a>
            </div>
        </div>

    @endif

    <form name="submitForm" id="submitForm" action="{{ url("library/testcase/create") }}" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input type="hidden" id="name1" name="name1" value="">
            <input type="hidden" id="testSuite1" name="testSuite1" value="">
            <input type="hidden" id="testCaseCount" name="testCaseCount" value="1">
            @if (isset($selectedSuite))
                <input type="hidden" id="selectedSuite" name="selectedSuite" value="{{ $selectedSuite->TestSuite_id }}">
            @endif
    </form>

    <div class="col-md-12" style="height:20px;"></div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Test case name</th>
                        <th>Test Suite</th>
                        <th>Last update</th>
                    </tr>
                </thead>
                <tbody>
                    <td class="col-md-1">
                    </td>
                    <td class="col-md-4">
                        <input type="text" class="form-control" id="testname" name='testname' maxlength="45" value="{{ old('name') }}" placeholder="Enter test case name" autofocus>
                    </td>

                    <td  class="col-md-3">
                        <select class="form-control" id="suite1" name="testSuite1">
                                       @if (isset($testSuites))
                                           @foreach ($testSuites as $testSuite)
                                               <option value="{{ $testSuite->TestSuite_id }}" {{ isset($selectedSuite) && $selectedSuite->TestSuite_id == $testSuite->TestSuite_id ? 'selected=\"selected\"' : ''}}>{{ $testSuite->Name }} </option>
                                           @endforeach
                                       @endif
                        </select>
                    </td>
                    <td class="col-md-4"><button type="button" onclick="$('#name1').val($('#testname').val());$('#testSuite1').val($('#suite1').val());event.preventDefault();$('#submitForm').submit();"
                        class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button></td>
                    @if (isset($testCases))
                    <?php $id = 1; ?>
                        @foreach ($testCases as $testCase)
                            <tr>
                                <td class="col-md-1">{{ $id }}</td>
                                <td class="col-md-4"><a href="{{ url("library/testcase/detail/$testCase->TestCase_id")}}">{{ $testCase->Name }}</a></td>
                                <td class="col-md-3">{{ App\TestSuite::find($testCase->TestSuite_id)->Name }}</td>
                                <td class="col-md-4">{{ $testCase->LastUpdate }}</td>
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
        $('#myTable').DataTable( {
  "aoColumnDefs": [
      { 'bSortable': false, 'aTargets': [ 1 ] }
   ]});
    });
</script>

<script src="/js/jquery.are-you-sure.js"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            Stretchy.resize('select');
            $('form').areYouSure();
            var text_max = 1023;

            $('#description').keyup(function() {
                var text_length = $('#description').val().length;
                var text_remaining = text_max - text_length;

                $('#feedback1').html(text_remaining + ' characters remaining');
            });

            $('#goals').keyup(function() {
                var text_length = $('#goals').val().length;
                var text_remaining = text_max - text_length;

                $('#feedback2').html(text_remaining + ' characters remaining');
            });

            $('#goals').keyup();
            $('#description').keyup();
        });

        function addNewTestCase() {
            $('#name1').val($('#testname').val());
            $('#testSuite1').val($('#suite1').val());
            event.preventDefault();
            $('#submitForm').submit();
        }
    </script>

@endsection
