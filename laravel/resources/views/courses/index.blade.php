@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto my-12 p-6">
    
    {{-- Gestion des notifications --}}
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

    {{-- En-tête de page --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-6">
        <div>
            <h2 class="text-4xl font-black text-gray-800 tracking-tight uppercase">Liste des Courses</h2>
            <p class="text-gray-500 mt-2">Découvrez les défis à venir et inscrivez-vous.</p>
        </div>

        {{-- Bouton Créer (Responsables uniquement) --}}
        @if(DB::table('vik_responsable_course')->where('UTI_ID', Auth::id())->exists())
            <a href="{{ route('courses.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-4 bg-black text-white rounded-xl font-bold hover:bg-green-600 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                CRÉER UNE COURSE
            </a>
        @endif
    </div>

    {{-- Grille des courses --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @foreach($courses as $course)
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 flex flex-col">
                
                {{-- 1. En-tête de la carte (Type, ID, Nom, Orga) --}}
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
                </div>

                <div class="p-6 space-y-6 flex-grow">
                    
                    {{-- 2. Dates et Lieu --}}
                    <div class="flex items-center justify-between text-sm text-gray-700 bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📍</span>
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

                    {{-- 3. Grille d'infos détaillées --}}
                    <div class="grid grid-cols-2 gap-4">
                        
                        {{-- Colonne Gauche : Tarifs --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b pb-1">💰 Tarification</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between">
                                    <span>Adulte:</span>
                                    <span class="font-bold text-green-700">{{ number_format($course->COU_PRIX, 2) }} €</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Enfant:</span>
                                    <span class="font-bold text-gray-700">{{ $course->COU_PRIX_ENFANT ? number_format($course->COU_PRIX_ENFANT, 2).' €' : '-' }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Repas:</span>
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

                        {{-- Colonne Droite : Conditions d'âge --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b pb-1">🔞 Conditions d'âge</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between">
                                    <span>Min global:</span>
                                    <span class="font-bold">{{ $course->COU_AGE_MIN }} ans</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Seul dès:</span>
                                    <span class="font-bold">{{ $course->COU_AGE_SEUL }} ans</span>
                                </li>
                                @if($course->COU_AGE_ACCOMPAGNATEUR)
                                <li class="flex justify-between">
                                    <span>Accompagnateur:</span>
                                    <span class="font-bold">{{ $course->COU_AGE_ACCOMPAGNATEUR }} ans</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- 4. Logistique (Participants et Équipes) --}}
                    <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                         
                        <h4 class="text-xs font-black text-yellow-600 uppercase tracking-widest mb-3 border-b border-yellow-200 pb-1">⚡ Format & Capacité</h4>
                        <div class="grid grid-cols-3 gap-2 text-center divide-x divide-yellow-200">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Par Équipe</p>
                                <p class="font-black text-lg text-gray-800">{{ $course->COU_PARTICIPANT_PAR_EQUIPE_MAX }}</p>
                                <p class="text-[10px] text-gray-400">pers. max</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Participants</p>
                                <p class="font-black text-lg text-gray-800">0 <span class="text-xs font-normal text-gray-400">/ {{ $course->COU_PARTICIPANT_MAX }}</span></p>
                                <p class="text-[10px] text-gray-400">Min: {{ $course->COU_PARTICIPANT_MIN }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Équipes</p>
                                <p class="font-black text-lg text-gray-800">0 <span class="text-xs font-normal text-gray-400">/ {{ $course->COU_EQUIPE_MAX }}</span></p>
                                <p class="text-[10px] text-gray-400">Min: {{ $course->COU_EQUIPE_MIN }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- 5. Pied de carte (Actions) --}}
                <div class="p-6 pt-0 mt-auto">
                    @if(DB::table('vik_responsable_course')->where('UTI_ID', Auth::id())->exists())
                        <div class="grid grid-cols-5 gap-3">
                            <button class="col-span-4 bg-black text-white py-4 rounded-2xl font-bold hover:bg-green-600 transition-colors shadow-lg flex justify-center items-center group">
                                <span>S'inscrire</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                            <a href="{{ route('courses.edit', [$course->RAI_ID, $course->COU_ID]) }}" class="col-span-1 bg-gray-100 text-gray-600 rounded-2xl flex items-center justify-center hover:bg-yellow-400 hover:text-white transition-all shadow-inner border border-gray-200" title="Modifier">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </div>
                    @else
                        <button class="w-full bg-black text-white py-4 rounded-2xl font-bold hover:bg-green-600 transition-colors shadow-lg flex justify-center items-center group">
                            <span>S'inscrire</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection