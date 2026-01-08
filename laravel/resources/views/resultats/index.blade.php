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
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('csv-modal').classList.remove('hidden')" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg uppercase text-xs tracking-widest transform hover:-translate-y-1">
                    📤 Importer CSV
                </button>
                <button form="resultats-form" type="submit" class="bg-black text-white px-8 py-3 rounded-xl font-bold hover:bg-green-600 transition-all shadow-lg uppercase text-xs tracking-widest transform hover:-translate-y-1">
                    Enregistrer les résultats
                </button>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded-2xl font-bold mb-8 text-center shadow-lg animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 text-white p-4 rounded-2xl font-bold mb-8 text-center shadow-lg animate-fade-in">
            {{ session('error') }}
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
                                           pattern="\d{2}:\d{2}:\d{2}"
                                           title="Format: HH:MM:SS (ex: 01:23:45)"
                                           maxlength="8"
                                           class="time-input p-2 font-mono text-sm border border-gray-200 rounded-xl w-28 text-center focus:ring-2 focus:ring-black outline-none"
                                           onclick="event.stopPropagation()">
                                @else
                                    <span class="font-mono font-bold text-gray-700">{{ $equipe->resultat_cache->RES_TEMPS ?? '--:--:--' }}</span>
                                @endif
                            </div>

                            <div class="text-right w-20">
                                <span class="block text-[10px] font-black text-gray-300 uppercase tracking-widest">Points</span>
                                @if($canManage)
                                    <input type="number" name="resultats[{{ $equipe->EQU_ID }}][points]"
                                           value="{{ $equipe->resultat_cache->RES_POINT ?? 0 }}"
                                           min="0"
                                           max="9999"
                                           class="p-2 font-bold border border-gray-200 rounded-xl w-20 text-center focus:ring-2 focus:ring-black outline-none"
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

<!-- Modal d'import CSV -->
<div id="csv-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fade-in">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-black text-white uppercase tracking-tight">Importer les résultats (CSV)</h2>
                <button type="button" onclick="document.getElementById('csv-modal').classList.add('hidden')" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-8">
            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6 rounded-r-xl">
                <h3 class="font-black text-blue-900 text-sm uppercase tracking-wider mb-2">Format du fichier CSV</h3>
                <p class="text-sm text-blue-800 mb-3">Le fichier CSV doit contenir les colonnes suivantes (dans n'importe quel ordre):</p>
                <div class="bg-white p-3 rounded-xl font-mono text-xs border border-blue-200">
                    <div class="grid grid-cols-4 gap-2 font-bold text-blue-900 mb-2">
                        <span>equ_id</span>
                        <span>rang</span>
                        <span>temps</span>
                        <span>points</span>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-gray-600">
                        <span>1</span>
                        <span>1</span>
                        <span>00:45:30</span>
                        <span>100</span>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-gray-600">
                        <span>2</span>
                        <span>2</span>
                        <span>00:52:15</span>
                        <span>90</span>
                    </div>
                </div>
                <p class="text-xs text-blue-700 mt-3">
                    <strong>Note:</strong> Le format du temps doit être HH:MM:SS (ex: 00:45:30)
                </p>
                <a href="{{ asset('exemple_import_resultats.csv') }}" download class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold text-xs mt-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Télécharger un fichier CSV exemple
                </a>
            </div>

            <form action="{{ route('resultats.import', [$course->RAI_ID, $course->COU_ID]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-3">Sélectionner le fichier CSV</label>
                    <div class="relative">
                        <input type="file" name="csv_file" accept=".csv,.txt" required
                               class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none font-medium text-sm
                                      file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold
                                      file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('csv-modal').classList.add('hidden')"
                            class="px-6 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-100 transition uppercase text-xs tracking-widest">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg uppercase text-xs tracking-widest transform hover:-translate-y-1">
                        Importer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleMembers(id) {
        const content = document.getElementById('members-' + id);
        const icon = document.getElementById('icon-' + id);

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('csv-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Formatage automatique du temps (HH:MM:SS)
    document.addEventListener('DOMContentLoaded', function() {
        const timeInputs = document.querySelectorAll('.time-input');

        timeInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Retirer tout sauf les chiffres

                if (value.length > 6) {
                    value = value.slice(0, 6);
                }

                let formatted = '';
                if (value.length > 0) {
                    formatted = value.slice(0, 2); // HH
                }
                if (value.length >= 3) {
                    formatted += ':' + value.slice(2, 4); // MM
                }
                if (value.length >= 5) {
                    formatted += ':' + value.slice(4, 6); // SS
                }

                e.target.value = formatted;
            });

            // Empêcher la saisie de caractères non numériques
            input.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>

<style>
    @keyframes slide-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down {
        animation: slide-down 0.3s ease-out forwards;
    }
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>
@endsection