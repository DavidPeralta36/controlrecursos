@extends('layouts.app')

@section('content')
    <div class="w-100 h-100">
        <programacion-page :user="{{ json_encode(Auth::user()) }}" :fuentes="{{ json_encode($fuentes) }}" :periodos="{{ json_encode($periodos) }}" :partidas="{{ json_encode($partidas) }}"/>
    </div>
@endsection