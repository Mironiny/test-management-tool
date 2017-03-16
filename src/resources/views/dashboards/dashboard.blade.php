@extends('layouts.mainLayout')

@section('title')
    Dashboard
@endsection

@section('sidemenu')
    <a href="#">Project progress</a>
    <a href="#">Test results</a>
    <a href="#">Proggress bar</a>
@endsection

@section('content')
    <div class="col-xs-12" style="height:65px;"></div>
    {{-- <span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span> --}}
    <div class="container">

        <div class="row">
            <div class="col-sm-5">
                @if (isset($pieRequirementsChart))
                    {!! $pieRequirementsChart->render() !!}
                @endif
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-6">
                @if (isset($barRequirementsChart))
                    {!! $barRequirementsChart->render() !!}
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                @if (isset($testRunChart))
                    {!! $testRunChart->render() !!}
                @endif
            </div>
        </div>


        {{-- <div class="row">
            <div class="col-sm-12">
                {!! $pieRequirementsChart->render() !!}
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-sm-12">
                {!! $barRequirementsChart->render() !!}
            </div>
        </div> --}}

    </div>

@endsection

@section('javascript')
    {!! Charts::assets() !!}
@endsection
