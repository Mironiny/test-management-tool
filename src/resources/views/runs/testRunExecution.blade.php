@extends('layouts.mainLayout')

@section('title')
    Test case create
@endsection

@section('sidemenu')
    <div id="sidemenu">
        <button type="button" class="btn btn-default btn-xs disabled">Not tested yet <span class="badge">9</span></button></br></br>
        <button type="button" class="btn btn-success btn-xs disabled">Pass <span class="badge">7</span></button></br></br>
        <button type="button" class="btn btn-danger btn-xs disabled">Fail <span class="badge">3</span></button></br></br>
        <button type="button" class="btn btn-default btn-xs disabled">Blocked <span class="badge">2</span></button>

        <hr />
        <button type="button" class="btn btn-default btn-xs" onclick="expand()">Expand all</button>
        <button type="button" class="btn btn-default btn-xs" onclick="collapse()">Collapse all</button>

    </div>
    <div class="list">
        @if (isset($testSuites))
            @foreach ($testSuites as $testSuite)
                <div class="ll">
                    <a><span style="font-size: 11px" class="glyphicon glyphicon-plus"></span> <strong>{{$testSuite->Name}}</strong></a>
                    <ul id="insideExecution">
                        @if (isset($testCases))
                            @foreach ($testCases as $testCase)
                                @if ($testCase->testSuite->TestSuite_id == $testSuite->TestSuite_id )
                                    <li><a href="#" style="padding: 0px 0px 0px 0px; {{ $testCase->TestCase_id == $selectedTestCase->TestCase_id ? 'color:white' : '' }}">{{ $testCase->Name }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endforeach
        @endif

</div>

@endsection

@section('content')
<div class="col-xs-12" style="height:40px;"></div>
<span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
<div class="container">

    @include('layouts.status')
    @include('layouts.formErrors')

@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('.list .ll a').click(function() {
                $(this).parent().find('ul').toggle();
                $(this).find("span").toggleClass('glyphicon glyphicon-plus glyphicon glyphicon-minus');
            });
        });

        function expand() {
            $('.list > .ll ul').css('display', '');
        }
        function collapse() {
            $('.list > .ll ul').css('display', 'none');
        }

    </script>
@endsection
