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

</div>


@endsection
