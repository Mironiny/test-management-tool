{{--    --}}
{{-- Sidemenu of library's pages --}}
{{--    --}}


<a href="{{ url('library')}}" style="{{ Request::is('library') ? 'color:white' : '' }}" >All tests</a>
<a href="#"><span class="fa fa-gear fa-fw"></span> Manage test suites</i></a>
<hr />
@if (isset($testSuites))
    @foreach ($testSuites as $testSuite)
        <a href="{{ url("library/filter/$testSuite->TestSuite_id")}}" style="{{ Request::is("library/filter/$testSuite->TestSuite_id") ? 'color:white' : '' }}">{{ $testSuite->Name }}</a>
    @endforeach
@endif
