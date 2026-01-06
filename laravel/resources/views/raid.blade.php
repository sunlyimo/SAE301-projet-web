@extends('layouts.app')

@section('title', 'Raids')

@section('content')
<!--TO DO : Filtre âge et type à faire-->

<div>
    <h1> Raids </h1>
    <p>Liste des raids disponibles</p>

        <div class="flex flex-wrap">
            @foreach ($raids as $raid)
                |<div class="bg-gray-300 rounded-md w-3xs m-2">
                    <h2> {{ $raid }} </h2>
                    <p class="text-bold"> courses</p>
                </div>
            @endforeach
        </div>

</div>
@endsection