@extends('layouts.app')

@section('title', 'Raids')

@section('content')
<!--TO DO : Filtre âge et type à faire-->

<div>
    <h1> Raids </h1>
    <p>Liste des raids disponibles</p>

        <div class="flex flex-wrap">
            @foreach ($raids as $raid)
                <div class="border border-black-300 rounded-md w-10xs m-2 p-2">
                    <h2> {{ $raid->RAI_NOM }} </h2>
                    @if($raid->RAI_INSCRI_DATE_DEBUT <= now() && $raid->RAI_INSCRI_DATE_FIN >= now())
                        <p class="text-bold"> En cours</p>
                    @endif
                    @if($raid->RAI_INSCRI_DATE_DEBUT > now())
                        <p class="text-bold"> Inscription à venir</p>
                    @endif
                    @if($raid->RAI_INSCRI_DATE_FIN < now())
                        <p class="text-bold"> Inscription terminée</p>
                    @endif
                    <p>Déroulement du {{ $raid->RAI_RAID_DATE_DEBUT }} au {{ $raid->RAI_RAID_DATE_FIN}}</p>
                    <p>Inscription du {{ $raid->RAI_INSCRI_DATE_DEBUT }} au {{ $raid->RAI_INSCRI_DATE_FIN}}</p>
                </div>
            @endforeach
        </div>

</div>
@endsection