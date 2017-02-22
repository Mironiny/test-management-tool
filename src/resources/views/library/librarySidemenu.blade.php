{{--    --}}
{{-- Sidemenu of library's pages --}}
{{--    --}}


<a href="{{ url('library')}}" style="{{ Request::is('library') ? 'color:white' : '' }}" >All tests</a>
<a href="{{ url('library/testsuite/create')}}"style="{{ Request::is('library/testsuite/create') ? 'color:white' : '' }}"><span class="fa fa-plus  fa-fw"></span> New test suite</i></a>
<hr />
@if (isset($testSuites))
    @foreach ($testSuites as $testSuite)
        <a href="{{ url("library/filter/$testSuite->TestSuite_id")}}" style="{{ Request::is("library/filter/$testSuite->TestSuite_id") ? 'color:white' : '' }}">{{ $testSuite->Name }}</a>
    @endforeach
@endif
