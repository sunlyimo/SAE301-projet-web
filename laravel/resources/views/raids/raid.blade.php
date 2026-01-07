@extends('layouts.app')

@section('title', 'Raids')

@section('content')
<div class="max-w-[90rem] mx-auto my-12 p-6">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-4xl font-black text-gray-800 tracking-tight uppercase">Les Raids</h1>
            <p class="text-gray-500 mt-2">Sélectionnez un événement pour voir les épreuves.</p>
        </div>
        
        {{-- Bouton Créer (si admin) --}}
        {{-- Tu pourras ajouter le bouton de création de Raid ici si besoin --}}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($raids as $raid)
            <div class="group bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                
                {{-- Nom --}}
                <div class="p-6 flex-grow">
                    
                    {{-- Status --}}
                    <div class="mb-4 flex justify-between items-start">
                        @php $now = now(); @endphp
                        @if($raid->RAI_INSCRI_DATE_DEBUT <= $now && $raid->RAI_INSCRI_DATE_FIN >= $now)
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700">
                                ● Inscriptions ouvertes
                            </span>
                        @elseif($raid->RAI_INSCRI_DATE_DEBUT > $now)
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-yellow-100 text-yellow-700">
                                ● À venir
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-500">
                                ● Terminé
                            </span>
                        @endif
                        
                        <span class="text-gray-300 font-black text-lg">#{{ $raid->RAI_ID }}</span>
                    </div>

                    {{-- Nom du Raid --}}
                    <h2 class="text-2xl font-black text-gray-900 mb-2 leading-tight uppercase">
                        {{ $raid->RAI_NOM }}
                    </h2>

                    <p class="text-sm font-bold text-gray-800">
                        {{ $raid->total_course }} courses
                    </p>

                    {{-- Dates de l'événement --}}
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center text-xs text-gray-500 uppercase font-bold mb-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Date de l'événement
                        </div>
                        <p class="text-sm font-bold text-gray-800">
                            Du {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT)->format('d/m/Y') }} 
                            au {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN)->format('d/m/Y') }}
                        </p>
                    </div>

                    {{-- Inscription --}}
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center text-xs text-gray-500 uppercase font-bold mb-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Date d'inscription
                        </div>
                        <p class="text-sm font-bold text-gray-800">
                            Du {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT)->format('d/m/Y') }} 
                            au {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                {{-- Partie Inférieure : Action --}}
                <div class="p-6 pt-0 mt-auto">
                    <a href="{{ route('raids.courses', $raid->RAI_ID) }}" 
                       class="block w-full py-3 bg-black text-white text-center rounded-xl font-bold text-sm hover:bg-green-600 transition-colors shadow-lg flex justify-center items-center group-hover:shadow-green-200">
                        VOIR LES COURSES
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection