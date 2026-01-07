@extends('layouts.app')

@section('title', 'Bienvenue')

@section('content')

<div class="relative min-h-[400px] overflow-hidden">
    
    <img src="{{ asset('images/foret.jpg') }}" alt="Forêt" class="absolute inset-0 w-full h-full object-cover">
    
    <div class="relative z-10 flex items-center justify-center h-full min-h-[400px]">
        <h1 class="text-6xl md:text-7xl lg:text-8xl font-bold text-white">CartoRun</h1>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Bienvenue sur CartoRun</h2>
            
            <p class="text-gray-700 mb-4">
                Gérez facilement vos inscriptions et suivez vos performances dans les raids et courses d'orientation. 
                Cette plateforme vous permet de :
            </p>

            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>Découvrir et vous inscrire aux raids et courses.</li>
                <li>Créer et gérer vos équipes, vos participants et vos documents.</li>
                <li>Suivre vos résultats et vos classements.</li>
                <li>Pour les organisateurs : créer des courses, gérer les inscriptions, les dossards et les résultats.</li>
            </ul>

            <p class="text-gray-700">
                Une application simple et complète pour tous les coureurs, clubs et organisateurs d'événements d'orientation.
            </p>
        </div>

        
        <div class="flex items-center justify-center lg:justify-end">
            <img src="{{ asset('images/logo.png') }}" alt="CartoRun Logo" class="w-64 h-auto">
        </div>
    </div>
</div>

<!-- Show the latest RAID -->

<div class="max-w-[90rem] mx-auto my-12 p-6 border-t border-gray-200 pb-6">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-10">
        <div>
            <h1 class="text-4xl font-black text-gray-800 tracking-tight uppercase">Les Raids à venir</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($raids->take(4) as $raid)
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
                            Du {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_DEBUT)->format('d/m/Y') }} 
                            au {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_FIN)->format('d/m/Y') }}
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