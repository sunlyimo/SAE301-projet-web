@extends('layouts.app')

@section('title', 'Raids')

@section('content')

<div class="max-w-6xl mx-auto my-12 p-6">
    <h1 class="font-extrabold"> Raids </h1>
    <p>Liste des raids disponibles</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($raids as $raid)
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all">
                    <h4 class="font-extrabold"> {{ $raid->RAI_NOM }} </h4>
                    @if($raid->RAI_INSCRI_DATE_DEBUT <= now() && $raid->RAI_INSCRI_DATE_FIN >= now())
                        <p class="font-bold"> Inscription en cours</p>
                    @endif
                    @if($raid->RAI_INSCRI_DATE_DEBUT > now())
                        <p class="font-bold"> Inscription à venir</p>
                    @endif
                    @if($raid->RAI_INSCRI_DATE_FIN < now())
                    <p class="font-bold"> Inscription terminée</p>
                    @endif
                    <p class="font-bold"> {{ $raid->total_course }} courses</p> <!--Show Race count-->
                    <p>Déroulement du {{ $raid->RAI_RAID_DATE_DEBUT }} au {{ $raid->RAI_RAID_DATE_FIN}}</p>
                    <p>Inscription du {{ $raid->RAI_INSCRI_DATE_DEBUT }} au {{ $raid->RAI_INSCRI_DATE_FIN}}</p>
                    <a style="text-decoration: none !important;" href="/raids/{{$raid->RAI_ID}}" class="w-full mt-6 bg-black text-white py-3 rounded-xl font-bold hover:bg-green-600 transition-colors p-4">
                        Voir les courses
                    </a>
                </div>
            @endforeach
        </div>

</div>
@endsection