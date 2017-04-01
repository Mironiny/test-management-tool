@extends('layouts.mainLayout')

@section('title')
    User
@endsection

@section('content')
<div class="container">
    <div class="col-md-12" style="height:65px;"></div>

    <p><b>Your username:</b> {{ $user->name}}</p>
    <p><b>Your email: </b>{{ $user->email}}</p>
    <p><b>Your registration date: </b>{{ $user->created_at}}</p>

    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#api">Show api token</button>
    <div id="api" class="collapse">
        <p><b>Your api token: </b>{{ $user->api_token}}</p>
    </div>


</div>


@endsection
