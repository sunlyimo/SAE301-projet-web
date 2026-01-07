@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-12 p-6">
    <div class="flex justify-between items-end mb-8">
        <div>
            <a href="{{ route('raids.courses', $course->RAI_ID) }}" class="text-gray-400 hover:text-black font-bold text-sm mb-2 inline-flex items-center group">
                <svg class="w-4 h-4 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour aux courses
            </a>
            <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Classement Officiel</h1>
            <p class="text-gray-500 font-bold flex items-center gap-2">
                <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                {{ $course->COU_NOM }}
            </p>
        </div>
        
        @if($canManage)
            <button form="resultats-form" type="submit" class="bg-black text-white px-8 py-3 rounded-xl font-bold hover:bg-green-600 transition-all shadow-lg uppercase text-xs tracking-widest transform hover:-translate-y-1">
                Enregistrer les résultats
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded-2xl font-bold mb-8 text-center shadow-lg animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    <form id="resultats-form" action="{{ route('resultats.store', [$course->RAI_ID, $course->COU_ID]) }}" method="POST">
        @csrf
        <div class="space-y-4">
            @forelse($equipes as $index => $equipe)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:border-gray-300 transition-all">
                    <div class="p-5 flex items-center justify-between cursor-pointer group" onclick="toggleMembers('{{ $equipe->getAttribute('EQU_ID') }}')">
                        <div class="flex items-center gap-6">
                        
                            <div class="w-12 text-center">
                                @if($canManage)
                                    <input type="number" name="resultats[{{ $equipe->EQU_ID }}][rang]" 
                                           value="{{ $equipe->resultat_cache->RES_RANG ?? ($index + 1) }}" 
                                           class="w-12 p-2 text-center font-black border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none"
                                           onclick="event.stopPropagation()">
                                @else
                                    <span class="text-2xl font-black {{ ($equipe->resultat_cache->RES_RANG ?? ($index+1)) <= 3 ? 'text-yellow-500' : 'text-gray-300' }}">
                                        #{{ $equipe->resultat_cache->RES_RANG ?? ($index + 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 shadow-inner">
                                    @if($equipe->EQU_IMAGE)
                                        <img src="{{ asset('storage/' . $equipe->EQU_IMAGE) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl">🚩</span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-black text-gray-900 uppercase leading-tight group-hover:text-blue-600 transition-colors">{{ $equipe->EQU_NOM }}</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Cliquez pour voir les membres</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <span class="block text-[10px] font-black text-gray-300 uppercase tracking-widest">Temps</span>
                                @if($canManage)
                                    <input type="text" name="resultats[{{ $equipe->EQU_ID }}][temps]" 
                                           value="{{ $equipe->resultat_cache->RES_TEMPS ?? '' }}" 
                                           placeholder="00:00:00"
                                           class="p-2 font-mono text-sm border border-gray-200 rounded-xl w-28 text-center focus:ring-2 focus:ring-black outline-none"
                                           onclick="event.stopPropagation()">
                                @else
                                    <span class="font-mono font-bold text-gray-700">{{ $equipe->resultat_cache->RES_TEMPS ?? '--:--:--' }}</span>
                                @endif
                            </div>

                            <div class="text-right w-16">
                                <span class="block text-[10px] font-black text-gray-300 uppercase tracking-widest">Points</span>
                                @if($canManage)
                                    <input type="number" name="resultats[{{ $equipe->EQU_ID }}][points]" 
                                           value="{{ $equipe->resultat_cache->RES_POINT ?? 0 }}" 
                                           class="p-2 font-bold border border-gray-200 rounded-xl w-16 text-center focus:ring-2 focus:ring-black outline-none"
                                           onclick="event.stopPropagation()">
                                @else
                                    <span class="font-bold text-blue-600">{{ $equipe->resultat_cache->RES_POINT ?? 0 }}</span>
                                @endif
                            </div>

                            <div class="ml-2">
                                <svg id="icon-{{ $equipe->EQU_ID }}" class="w-6 h-6 text-gray-200 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="members-{{ $equipe->EQU_ID }}" class="hidden bg-gray-50 border-t border-gray-100 p-6 animate-slide-down">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($equipe->chef)
                            <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-yellow-100 shadow-sm">
                                <div class="w-8 h-8 rounded-xl bg-yellow-400 flex items-center justify-center text-white text-xs shadow-inner">👑</div>
                                <div>
                                    <p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest leading-none">Chef d'équipe</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $equipe->chef->UTI_PRENOM }} {{ $equipe->chef->UTI_NOM }}</p>
                                </div>
                            </div>
                            @endif
                            @foreach($equipe->membres_list as $membre)
                                @if($equipe->chef && $membre->UTI_ID != $equipe->chef->UTI_ID)
                                    <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm">
                                        <div class="w-8 h-8 rounded-xl bg-blue-500 flex items-center justify-center text-white text-xs shadow-inner">👤</div>
                                        <div>
                                            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest leading-none">Coéquipier</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $membre->UTI_PRENOM }} {{ $membre->UTI_NOM }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Aucune équipe pour le moment.</p>
                </div>
            @endforelse
        </div>
    </form>
</div>

<script>
    function toggleMembers(id) {
        const content = document.getElementById('members-' + id);
        const icon = document.getElementById('icon-' + id);
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>

<style>
    @keyframes slide-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down {
        animation: slide-down 0.3s ease-out forwards;
    }
</style>
@endsection