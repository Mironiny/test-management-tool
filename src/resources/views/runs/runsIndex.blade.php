@extends('layouts.mainLayout')

@section('title')
    Test run
@endsection

@section('content')
    <div class="container">
        <div class="col-md-12" style="height:65px;"></div>

        @include('layouts.status')

        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <a href="{{ url('sets_runs/set/create')}}" class="btn btn-primary btn-block {{ isset($selectedProject) ? '' : 'disabled' }}" role="button">
                    <span class="glyphicon glyphicon-plus"></span> New test set
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
                        <th>Test Set name</th>
                        <th>Test runs</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($testSets))
                        <?php $id = 1; ?>
                        @foreach ($testSets as $testSet)
                        <tr>
                            <td>{{ $id }}</td>
                            <td><a href="{{ url("sets_runs/set/detail/$testSet->TestSet_id")}}">{{ $testSet->Name }}</a></td>
                            <td>{{ App\TestSet::find($testSet->TestSet_id)->testRuns()->count() }}</td>
                        </tr>
                        <?php $id++; ?>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        </div>

@endsection

@section('javascript')
    <script>
    $(document).ready(function(){
        $('#myTable').DataTable();
    });
    </script>

@endsection
