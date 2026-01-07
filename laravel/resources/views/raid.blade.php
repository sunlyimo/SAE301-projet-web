@extends('layouts.app')

@section('title', 'Raids')

@section('content')

<div>
    <h1 class="font-extrabold"> Raids </h1>
    <p>Liste des raids disponibles</p>

        <div class="flex flex-wrap">
            @foreach ($raids as $raid)
                <div class="border border-black-300 rounded-md w-10xs m-2 p-2">
                    <h2 class="font-extrabold"> {{ $raid->RAI_NOM }} </h2>
                    @if($raid->RAI_INSCRI_DATE_DEBUT <= now() && $raid->RAI_INSCRI_DATE_FIN >= now())
                        <p class="font-bold"> Inscription en cours</p>
                    @endif
                    @if($raid->RAI_INSCRI_DATE_DEBUT > now())
                        <p class="font-bold"> Inscription à venir</p>
                    @endif
                    @if($raid->RAI_INSCRI_DATE_FIN < now())
                    <p class="font-bold"> Inscription terminée</p>
                    @endif
                    <p class="font-bold"> {{ $raid->total_course }} courses</p> <!--Affiche le nombre de courses-->
                    <p>Déroulement du {{ $raid->RAI_RAID_DATE_DEBUT }} au {{ $raid->RAI_RAID_DATE_FIN}}</p>
                    <p>Inscription du {{ $raid->RAI_INSCRI_DATE_DEBUT }} au {{ $raid->RAI_INSCRI_DATE_FIN}}</p>
                </div>
            @endforeach
        </div>

</div>
@endsection