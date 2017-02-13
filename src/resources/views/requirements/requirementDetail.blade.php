@extends('layouts.mainLayout')

@section('title')
    Requirements - Detail
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url('requirements') }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span>  @lang('layout.back')</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
                <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Requirement <span class="glyphicon glyphicon-chevron-right"></span> detail</a>
            </div>
        </div>
    </div>

    </br>
    </br>

    <form class="form-horizontal" action="{{ url("requirements/update/$requirementDetail->TestRequirement_id")}}" method="POST"> {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><span class="text-danger">*</span>Requirement name:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="name" disabled="disabled" name='name' maxlength="45" value="{{ $requirementDetail->Name }}" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="description">Requirement description:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" name="description" placeholder="Enter requirement description">{{ $requirementDetail->RequirementDescription }}</textarea>
                <div class="pull-right">
                    <div id="description_feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-2">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-2">
                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default" role="button">Delete project</button>
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
                        <p>Do you really want to delete requirement?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url("requirements/terminate/$requirementDetail->TestRequirement_id")}}" class="btn btn-default" role="button">Yes</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@section('javascript')
<script src="/js/jquery.are-you-sure.js"></script>

<script>
    $(document).ready(function() {
        $('form').areYouSure();
        var text_max = 1023;

        $('#description').keyup(function() {
            var text_length = $('#description').val().length;
            var text_remaining = text_max - text_length;

            $('#description_feedback').html(text_remaining + ' characters remaining');
        });

        $('#description').keyup();
    });
</script>

@endsection
