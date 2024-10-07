@extends('layouts.app')

@section('content')
    <div class="w-100 h-100">
        <catalogos-page :user="{{ json_encode(Auth::user()) }}" :partidas="{{ json_encode($partidas) }}" :capitulos="{{ json_encode($capitulos) }}"/>
    </div>
@endsection