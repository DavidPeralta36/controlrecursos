@extends('layouts.app')

@section('content')
    <div class="w-100 h-100">
        <carga-page :user="{{ json_encode(Auth::user()) }}" :fuentes="{{ json_encode($fuentes) }}" :periodos="{{ json_encode($periodos) }}"/>
    </div>
@endsection
