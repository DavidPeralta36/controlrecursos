@extends('layouts.app')

@section('content')
    <div class="w-100 h-100">
        <catalogos-page :user="{{ json_encode(Auth::user()) }}" :fuentes="{{ json_encode($fuentes) }}" :periodos="{{ json_encode($periodos) }}"/>
    </div>
@endsection