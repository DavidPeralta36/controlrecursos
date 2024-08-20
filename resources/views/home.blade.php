@extends('layouts.app')

@section('content')
<div class="container w-100 h-100">
    <h1>Lista de usuarios desde blade</h1>
    <ul>
        @foreach($users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
    </ul>
    <home-page/>
</div>
@endsection
