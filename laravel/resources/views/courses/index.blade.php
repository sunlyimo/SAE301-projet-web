@extends('layouts.app')

@section('content')

@php
    $userId = Auth::id();
    $isRaidManager = DB::table('vik_responsable_raid')->where('UTI_ID', $userId)->exists();
    $isCourseManager = DB::table('vik_responsable_course')->where('UTI_ID', $userId)->exists();
    $canManage = $isRaidManager || $isCourseManager;
@endphp

<div class="max-w-7xl mx-auto my-12 p-6">
    
    {{-- Notifications --}}
    <div class="flex justify-between items-center mb-10">
        <div class="w-full max-w-2xl">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-md flex justify-between items-center animate-bounce">
                    <div class="flex items-center">
                        <span class="text-xl mr-2">✅</span>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-900 font-bold hover:text-green-700">&times;</button>
                </div>
            @endif
        </div>
    </div>

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-6">
        <div>
            @if(isset($raid))
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('raids.index') }}" class="text-gray-400 hover:text-black transition-colors flex items-center gap-1 font-bold text-sm group">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Retour aux Raids
                    </a>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded uppercase">
                        Raid #{{ $raid->RAI_ID }}
                    </span>
                </div>
                <h2 class="text-4xl font-black text-gray-800 tracking-tight uppercase leading-none">
                    Courses du {{ $raid->RAI_NOM }}
                </h2>
                <p class="text-gray-500 mt-2">Voici les épreuves disponibles pour ce raid.</p>
            @else
                <h2 class="text-4xl font-black text-gray-800 tracking-tight uppercase">Aucune Course disponible</h2>
            @endif
        </div>

        @if($isRaidManager)
            <a href="{{ route('courses.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-4 bg-black text-white rounded-xl font-bold hover:bg-green-600 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                AJOUTER UNE COURSE
            </a>
        @endif
    </div>

    @if($courses->isEmpty())
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-2xl text-gray-400 font-bold mb-4">Aucune course n'est encore configurée pour ce raid.</p>
            @if($isRaidManager)
                <p class="text-gray-500">Utilisez le bouton "Ajouter une course" ci-dessus pour commencer.</p>
            @elseif($isCourseManager)
                 <p class="text-gray-500">Aucune course disponible. Seul un Responsable de Raid peut en créer une.</p>
            @else
                <a href="{{ route('raids.index') }}" class="text-black underline font-bold hover:text-green-600">Retourner à la liste des raids</a>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @foreach($courses as $course)
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 flex flex-col h-full">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-black text-white text-xs font-black rounded uppercase tracking-widest">
                                {{ $course->type->TYP_LIBELLE ?? 'Course' }}
                            </span>
                            <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs font-bold rounded uppercase">
                                #{{ $course->RAI_ID }}-{{ $course->COU_ID }}
                            </span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-bold text-gray-400 uppercase">Difficulté</span>
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $course->DIF_NIVEAU ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-gray-900 mb-1">{{ $course->COU_NOM }}</h3>
                    <p class="text-sm text-gray-500 italic flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Organisé par : {{ $course->responsable->UTI_PRENOM ?? 'Inconnu' }} {{ $course->responsable->UTI_NOM ?? '' }}
                    </p>
                    <p class="text-sm text-gray-500 italic flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Club : {{ $course->responsable->clubAsCoureur->club->CLU_NOM ?? $course->responsable->clubs->first()->CLU_NOM ?? 'Aucun club' }}
                    </p>
                    <p class="text-sm text-gray-500 italic flex items-center">
                        Email : {{ $course->responsable->UTI_EMAIL ?? 'Inconnu' }}
                    </p>
                    <p class="text-sm text-gray-500 italic flex items-center">
                        Contact : {{ $course->responsable->UTI_TELEPHONE ?? 'Inconnu' }}
                    </p>
                </div>

                <div class="p-6 space-y-6 flex-grow">
                    <div class="flex items-center justify-between text-sm text-gray-700 bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <div class="flex items-center">
                            <div>
                                <p class="font-bold text-blue-900">Lieu</p>
                                <p>{{ $course->COU_LIEU }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-900">Départ</p>
                            <p>{{ \Carbon\Carbon::parse($course->COU_DATE_DEBUT)->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Fin: {{ \Carbon\Carbon::parse($course->COU_DATE_FIN)->format('d/m H:i') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b pb-1">Tarification</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between">
                                    <span>Adulte :</span>
                                    <span class="font-bold text-green-700">{{ number_format($course->COU_PRIX, 2) }} €</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Enfant :</span>
                                    <span class="font-bold text-gray-700">{{ $course->COU_PRIX_ENFANT ? number_format($course->COU_PRIX_ENFANT, 2).' €' : '-' }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Repas :</span>
                                    <span class="font-bold text-blue-600">{{ $course->COU_REPAS_PRIX ? number_format($course->COU_REPAS_PRIX, 2).' €' : 'Non inclus' }}</span>
                                </li>
                                @if($course->COU_REDUCTION > 0)
                                    <li class="flex justify-between pt-2 mt-1 border-t border-gray-200">
                                        <span class="text-red-500 font-bold">Réduction:</span>
                                        <span class="font-bold text-red-500">-{{ number_format($course->COU_REDUCTION, 2) }} €</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b pb-1">Conditions d'âge</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between">
                                    <span>Min global :</span>
                                    <span class="font-bold">{{ $course->COU_AGE_MIN }} ans</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Seul dès :</span>
                                    <span class="font-bold">{{ $course->COU_AGE_SEUL }} ans</span>
                                </li>
                                @if($course->COU_AGE_ACCOMPAGNATEUR)
                                <li class="flex justify-between">
                                    <span>Accompagnateur :</span>
                                    <span class="font-bold">{{ $course->COU_AGE_ACCOMPAGNATEUR }} ans</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest mb-3 border-b border-gray-200 pb-1">Format & Capacité</h4>
                        <div class="grid grid-cols-3 gap-2 text-center divide-x divide-gray-200">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Par Équipe</p>
                                <p class="font-black text-lg text-gray-800">{{ $course->COU_PARTICIPANT_PAR_EQUIPE_MAX }}</p>
                                <p class="text-[14px] text-gray-400">pers. max</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Participants</p>
                                <p class="font-black text-lg text-gray-800">0 <span class="text-xs font-normal text-gray-400">/ {{ $course->COU_PARTICIPANT_MAX }}</span></p>
                                <p class="text-[14px] text-gray-400">Min : {{ $course->COU_PARTICIPANT_MIN }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Équipes</p>
                                <p class="font-black text-lg text-gray-800">{{ $course->equipes_count ?? 0 }} <span class="text-xs font-normal text-gray-400">/ {{ $course->COU_EQUIPE_MAX }}</span></p>
                                <p class="text-[14px] text-gray-400">Min : {{ $course->COU_EQUIPE_MIN }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0 mt-auto">
                    <div class="mb-3">
                        <a href="{{ route('resultats.index', [$course->RAI_ID, $course->COU_ID]) }}" 
                           class="w-full flex items-center justify-center gap-2 bg-yellow-400 text-black py-2 rounded-xl font-black text-xs hover:bg-yellow-500 transition-colors uppercase tracking-wide shadow-sm">
                           Classement
                        </a>
                    </div>
                    @php 
                        $monEquipe = $course->equipeDuUser(); 
                    @endphp

                    <div class="flex gap-2">
                        @if($monEquipe)
                            <a href="{{ route('teams.show', [$course->RAI_ID, $course->COU_ID, $monEquipe->EQU_ID]) }}" 
                               class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg flex justify-center items-center group text-sm uppercase tracking-tighter">
                                <span>Voir mon équipe</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('courses.inscription', [$course->RAI_ID, $course->COU_ID]) }}" 
                               class="flex-1 bg-black text-white py-3 rounded-xl font-bold hover:bg-green-600 transition-colors shadow-lg flex justify-center items-center group text-sm uppercase tracking-tighter">
                                <span>S'inscrire (Créer une équipe)</span>
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        @endif

                        @if($canManage)
                            <a href="{{ route('courses.edit', [$course->RAI_ID, $course->COU_ID]) }}" 
                               class="w-12 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center hover:bg-yellow-400 hover:text-white transition-all shadow-inner border border-gray-200" 
                               title="Modifier la course">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection