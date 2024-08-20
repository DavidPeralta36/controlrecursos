@extends('layouts.app')

@section('content')
    <div class="container w-100 h-100">
        <home-page :user="{{ json_encode(Auth::user()) }}"/> 
    </div>
@endsection
