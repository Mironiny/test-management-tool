@extends('layouts.mainLayout')

@section('title')
    Projects - detail
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    @include('layouts.formErrors')

    <div class="row">
        <div class="col-md-5">
            <a href="{{ url('projects') }}" class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="pull-right">
            <a href="" class="btn btn-default disabled" style="cursor:default;" role="button">Projecs <span class="glyphicon glyphicon-chevron-right"></span> detail</a>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="height:10px;"></div>
    <form action="{{ url("projects/update/$projectDetail->SUT_id")}}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="name"><span class="text-danger">*</span>Project name:</label>
                    <input type="text" class="form-control" id="name" name="name" disabled="disabled" value="{{ $projectDetail->Name }}">
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="description">Project description:</label>
                    <textarea class="form-control" rows="5" id="description" name="description" placeholder="Enter project description">{{ $projectDetail->ProjectDescription }}</textarea>
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="test">Testing description:</label>
                    <textarea class="form-control" rows="5" id="test" name='testDescription' placeholder="Enter testing description">{{ $projectDetail->TestingDescription }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary btn-block">Save changes</button>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success btn-block" role="button">Terminate project</button>
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
              <p>Do you really want to terminate project?</p>
            </div>
            <div class="modal-footer">
              <a href="{{ url("projects/terminate/$projectDetail->SUT_id")}}" class="btn btn-default" role="button">Yes</a>
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
        </div>

    </form>
</div>



@endsection
