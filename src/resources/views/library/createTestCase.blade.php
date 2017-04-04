@extends('layouts.mainLayout')

@section('title')
    Test case create
@endsection

@section('sidemenu')
    @include('library.librarySidemenu')
@endsection

@section('content')
<div class="col-xs-12" style="height:40px;"></div>
<span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
<div class="container">

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ URL::previous() }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Test case <span class="glyphicon glyphicon-chevron-right"></span> create</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="height:10px;"></div>
    <br/>
    <br/>

    <form class="form-horizontal" action="{{ url('library/testcase/create')}}" method="POST">
        {{ csrf_field() }}

        <input type="hidden" id="testCaseCount" name="testCaseCount" value="1">

         <div id="clonedSection1" class="clonedSection">

        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Test case name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name1" name='name1' maxlength="45" value="{{ old('name') }}" placeholder="Enter test case name" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="Suite"><span class="text-danger">*</span>Test suite:</label>
            <div class="col-sm-10">
                <select class="form-control" id="suite1" name="testSuite1">
                   @if (isset($testSuites))
                       @foreach ($testSuites as $testSuite)
                           <option value="{{ $testSuite->TestSuite_id }}" {{ isset($selectedSuite) && $selectedSuite == $testSuite->TestSuite_id ? 'selected=\"selected\"' : ''}}>{{ $testSuite->Name }} </option>
                       @endforeach
                   @endif
               </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="description"><span data-toggle="tooltip" data-placement="top" title="What is the purpose of this test? Give tester test overview." class="glyphicon glyphicon-info-sign"></span> Test case description:
            </label>
            <div class="col-sm-10">
                <textarea  maxlength="1023" class="form-control" id="description1" name='description1' placeholder="Enter test case description">{{ old('description') }}</textarea>
                <div class="pull-right">
                    <div id="feedback1"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="What should tester do before test execution?" class="glyphicon glyphicon-info-sign"></span> Test case prefixes:</label>
            <div class="col-sm-10">
                <textarea maxlength="1023" class="form-control" id="prefixes1" name='prefixes1' placeholder="Enter test case prefixes">{{ old('prefixes') }}</textarea>
                <div class="pull-right">
                    <div id="feedback2"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="steps"><span data-toggle="tooltip" data-placement="top" title="What should tester do exactly?" class="glyphicon glyphicon-info-sign"></span> Test case steps:</label>
            <div class="col-sm-10">
                <textarea maxlength="1023" class="form-control" id="steps1" name='steps1' placeholder="Enter ordered steps of test case">{{ old('steps') }}</textarea>
                <div class="pull-right">
                    <div id="feedback4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="steps"><span data-toggle="tooltip" data-placement="top" title="What should tester check? What should be done?" class="glyphicon glyphicon-info-sign"></span> Test case expected result:</label>
            <div class="col-sm-10">
                <textarea maxlength="1023" class="form-control" id="expectedResult1" name='expectedResult1' placeholder="Enter expected result">{{ old('expectedResult') }}</textarea>
                <div class="pull-right">
                    <div id="feedback5"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="test"><span data-toggle="tooltip" data-placement="top" title="What should tester do after test execution?" class="glyphicon glyphicon-info-sign"></span> Test case suffixes:</label>
            <div class="col-sm-10">
                <textarea maxlength="1023" class="form-control" id="suffixes1" name='suffixes1' placeholder="Enter test case suffixes">{{ old('suffixes') }}</textarea>
                <div class="pull-right">
                    <div id="feedback3"></div>
                </div>
            </div>
        </div>
        <br/><hr/><br/>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <input type="button" id="btnAdd" class="btn btn-default" value="add another test case" />
            <input type="button" id="btnDel" class="btn btn-default" value="remove last test case" disabled="disabled" />
        </div>
    </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection

@section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>


<script>
    $(document).ready(function() {

        // -------------------------------------------------

            $("#btnAdd").click(function() {
                var num     = $(".clonedSection").length;
                var newNum  = new Number(num + 1);
                $("#testCaseCount").val(newNum);
                var newSection = $("#clonedSection" + num).clone().attr("id", "clonedSection" + newNum);

                newSection.children(":first").children(":nth-child(2)").children(":first").attr("id", "name" + newNum).attr("name", "name" + newNum).val("");
                newSection.children(":nth-child(2)").children(":nth-child(2)").children(":first").attr("id", "suite" + newNum).attr("name", "testSuite" + newNum);
                newSection.children(":nth-child(3)").children(":nth-child(2)").children(":first").attr("id", "description" + newNum).attr("name", "description" + newNum).val("");
                newSection.children(":nth-child(4)").children(":nth-child(2)").children(":first").attr("id", "prefixes" + newNum).attr("name", "prefixes" + newNum).val("");
                newSection.children(":nth-child(5)").children(":nth-child(2)").children(":first").attr("id", "steps" + newNum).attr("name", "steps" + newNum).val("");
                newSection.children(":nth-child(6)").children(":nth-child(2)").children(":first").attr("id", "expectedResult" + newNum).attr("name", "expectedResult" + newNum).val("");
                newSection.children(":nth-child(7)").children(":nth-child(2)").children(":first").attr("id", "suffixes" + newNum).attr("name", "suffixes" + newNum).val("");

                $(".clonedSection").last().append(newSection)

                $("#btnDel").removeAttr("disabled","");

                // if (newNum == 5)
                //     $("#btnAdd").attr("disabled","");
            });

            $("#btnDel").click(function() {
                var num = $(".clonedSection").length; // how many "duplicatable" input fields we currently have
                $("#clonedSection" + num).remove();     // remove the last element
                $("#testCaseCount").val(num - 1);

                // // enable the "add" button
                // $("#btnAdd").attr("disabled","");
                //
                // // if only one element remains, disable the "remove" button
                if (num-1 == 1)
                    $("#btnDel").attr("disabled","disabled");
            });

            // $("#btnDel").attr("disabled","disabled");

        // -------------------------------------------------

        Stretchy.resize('select');
        $('[data-toggle="tooltip"]').tooltip();
        $('form').areYouSure();
        var text_max = 1023;

        $('#description').keyup(function() {
            var text_length = $('#description').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback1').html(text_remaining + ' characters remaining');
        });

        $('#prefixes').keyup(function() {
            var text_length = $('#prefixes').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback2').html(text_remaining + ' characters remaining');
        });

        $('#steps').keyup(function() {
            var text_length = $('#steps').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback4').html(text_remaining + ' characters remaining');
        });

        $('#expectedResult').keyup(function() {
            var text_length = $('#expectedResult').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback5').html(text_remaining + ' characters remaining');
        });

        $('#suffixes').keyup(function() {
            var text_length = $('#suffixes').val().length;
            var text_remaining = text_max - text_length;

            $('#feedback3').html(text_remaining + ' characters remaining');
        });


        $('#prefixes').keyup();
        $('#description').keyup();
        $('#steps').keyup();
        $('#expectedResult').keyup();
        $('#suffixes').keyup();
    });
</script>

@endsection
