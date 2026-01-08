@extends('layouts.app')

@section('title', 'Raids')

@section('content')
<div class="max-w-[90rem] mx-auto my-12 p-6">
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-4xl font-black text-gray-800 tracking-tight uppercase">Les Raids</h1>
            <p class="text-gray-500 mt-2">Tous les raids à venir.</p>
        </div>
        
        {{-- Bouton Créer (visible si responsable de club ou administrateur) --}}
        @auth
            @php
                $isResponsible = false;
                try {
                    $isResponsible = \Illuminate\Support\Facades\DB::table('vik_responsable_club')
                        ->where('UTI_ID', auth()->id())
                        ->exists();
                    if (!$isResponsible) {
                        $isResponsible = \Illuminate\Support\Facades\DB::table('vik_administrateur')
                            ->where('UTI_ID', auth()->id())
                            ->exists();
                    }
                } catch (\Exception $e) {
                    // DB may be unavailable during setup; default to false
                    $isResponsible = false;
                }
            @endphp

            @if($isResponsible)
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('raids.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Créer un Raid
                    </a>
                </div>
            @endif
        @endauth
    </div>
    <div class="flex flex-wrap justify-center gap-6 mb-8">
        @forelse($raids as $raid)
            <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col relative">
                
                @auth
                    @if(auth()->user()->UTI_ID == $raid->UTI_ID)
                        <div class="absolute top-4 right-4 z-20">
                                     <a href="{{ route('raids.edit', ['raid_id' => $raid->RAI_ID]) }}" 
                               class="flex items-center justify-center w-10 h-10 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-full text-gray-700 hover:text-green-600 hover:border-green-600 shadow-sm transition-all"
                               title="Modifier ce raid">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                @endauth

                <div class="p-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                    @php $now = now(); @endphp
                    <div class="flex justify-between items-start mb-3">
                        @if($raid->RAI_INSCRI_DATE_DEBUT <= $now && $raid->RAI_INSCRI_DATE_FIN >= $now)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">
                                ● Inscriptions ouvertes
                            </span>
                        @elseif($raid->RAI_INSCRI_DATE_DEBUT > $now)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white">
                                ● Inscriptions non ouvertes
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-400 text-white">
                                ● Inscriptions terminées
                            </span>
                        @endif
                    </div>
                        <h3 class="text-xl font-black text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">
                            {{ $raid->RAI_NOM }}
                        </h3>

                        <p class="text-sm text-gray-500 italic flex items-center mb-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Organisé par : {{ $raid->responsable_nom ?? 'Inconnu' }}
                        </p>

                        <div class="flex items-center text-sm text-gray-600 mb-1">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $raid->RAI_LIEU }}
                        </div>
                        
                        <div class="flex items-center text-sm font-bold text-gray-900">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $raid->total_course }} {{ $raid->total_course > 1 ? 'courses' : 'course' }}
                        </div>
                    <div class="p-6 flex-grow space-y-4">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <div class="flex items-center text-xs text-blue-700 font-bold uppercase mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Événement
                            </div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT)->format('d/m/Y') }}
                                <span class="text-gray-400">→</span>
                                {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                            <div class="flex items-center text-xs text-purple-700 font-bold uppercase mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Inscriptions
                            </div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_DEBUT)->format('d/m/Y') }} 
                                <span class="text-gray-400">→</span>
                                {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_FIN)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('raids.courses', $raid->RAI_ID) }}" 
                           class="block w-full py-3 bg-gradient-to-r from-black to-gray-800 text-white text-center rounded-xl font-bold text-sm hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex justify-center items-center">
                            Voir les courses
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xl text-gray-400 font-bold">Aucun raid disponible pour le moment</p>
            </div>
        @endforelse
    </div>

</div>
@endsection