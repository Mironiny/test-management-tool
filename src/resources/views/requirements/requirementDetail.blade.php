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
        <form action="{{ url("requirements/update/$requirementDetail->TestRequirement_id")}}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="name"><span class="text-danger">*</span>Requirement name:</label>
                        <input type="text" class="form-control" id="name" disabled="disabled" name="name" value="{{ $requirementDetail->Name}}" placeholder="Enter requirement name" autofocus>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="description">Requirement description:</label>
                        <textarea class="form-control" rows="5" id="description" name="description" placeholder="Enter requirement description">{{ $requirementDetail->RequirementDescription}} </textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-danger btn-block" role="button">Terminate project</button>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-primary btn-block">Save changes</button>
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
