@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto my-12 p-6">

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">Mon Équipe #{{ $equipe->EQU_ID }}</h1>
                <p class="text-gray-500">{{ $course->COU_NOM }} - Course #{{ $equipe->COU_ID }} (Raid #{{ $equipe->RAI_ID }})</p>
                <div class="mt-2 flex items-center space-x-4">
                    <span class="text-sm font-bold text-gray-600">
                        Participants: <span class="text-blue-600">{{ $nbParticipants }}</span> / {{ $course->COU_PARTICIPANT_PAR_EQUIPE_MAX }}
                    </span>
                </div>
            </div>
            @if($isChef)
                <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-xl font-bold text-sm">👑 Vous êtes le Chef</span>
            @else
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-xl font-bold text-sm">👤 Vous êtes Membre</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- LISTE DES PARTICIPANTS --}}
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Composition de l'équipe
                </h3>

                {{-- Section Chef d'équipe --}}
                <div class="mb-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👑</span>
                            <div>
                                <span class="font-bold text-gray-900 block">{{ $equipe->chef->UTI_PRENOM }} {{ $equipe->chef->UTI_NOM }}</span>
                                <span class="text-xs text-yellow-600 font-bold uppercase">Chef d'équipe</span>
                            </div>
                        </div>
                    </div>

                    {{-- Checkbox participation du chef --}}
                    @if($isChef)
                    <form action="{{ route('teams.toggle-chef', [$equipe->RAI_ID, $equipe->COU_ID, $equipe->EQU_ID]) }}" method="POST" class="mt-3 pt-3 border-t border-yellow-200">
                        @csrf
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox"
                                   class="w-5 h-5 text-yellow-600 border-yellow-300 rounded focus:ring-yellow-500 cursor-pointer"
                                   {{ $chefParticipe ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-yellow-700">
                                {{ $chefParticipe ? '✓ Je participe à la course' : 'Participer à la course' }}
                            </span>
                        </label>
                        @error('chef')
                            <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </form>
                    @else
                        @if($chefParticipe)
                        <div class="mt-3 pt-3 border-t border-yellow-200">
                            <span class="text-sm font-bold text-green-600">✓ Participe à la course</span>
                        </div>
                        @endif
                    @endif
                </div>

                {{-- Liste des Participants --}}
                <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">Participants ({{ $equipe->membres->count() }})</h4>
                <ul class="space-y-2">
                    @forelse($equipe->membres as $membre)
                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div>
                                    <span class="font-bold text-gray-900">{{ $membre->utilisateur->UTI_PRENOM }} {{ $membre->utilisateur->UTI_NOM }}</span>
                                    <span class="block text-xs text-gray-400">&#64;{{ $membre->utilisateur->UTI_NOM_UTILISATEUR }}</span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center text-gray-400 text-sm">
                            Aucun participant pour le moment
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- FORMULAIRE D'AJOUT (Chef seulement) --}}
            @if($isChef)
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border-2 border-blue-100">
                {{-- Messages de succès/erreur --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-2 border-green-300 text-green-800 rounded-xl text-sm font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @error('pseudo')
                    <div class="mb-4 p-4 bg-red-50 border-2 border-red-300 text-red-800 rounded-xl text-sm font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror

                @error('chef')
                    <div class="mb-4 p-4 bg-red-50 border-2 border-red-300 text-red-800 rounded-xl text-sm font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror

                <h3 class="text-xl font-bold mb-2 text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Ajouter un participant
                </h3>
                <p class="text-sm text-gray-600 mb-6">Recherchez et ajoutez des coéquipiers par leur pseudo.</p>

                <form action="{{ route('teams.add', [$equipe->RAI_ID, $equipe->COU_ID, $equipe->EQU_ID]) }}" method="POST" class="space-y-4" id="addMemberForm">
                    @csrf

                    <div>
                        <label for="pseudo" class="block text-sm font-bold text-gray-700 mb-2">
                            Pseudo du participant
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none z-10">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text"
                                   id="pseudo"
                                   name="pseudo"
                                   placeholder="Rechercher par pseudo..."
                                   autocomplete="off"
                                   class="w-full p-4 pl-12 rounded-xl border-2 border-blue-200 focus:border-blue-500 outline-none transition-colors text-gray-900 placeholder-gray-400"
                                   required>

                            {{-- Liste déroulante des suggestions --}}
                            <div id="userSuggestions" class="hidden absolute z-20 w-full mt-1 bg-white border-2 border-blue-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Tapez au moins 2 caractères pour rechercher un utilisateur.</p>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-colors shadow-lg hover:shadow-xl flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        AJOUTER À L'ÉQUIPE
                    </button>
                </form>

                <script>
                    const pseudoInput = document.getElementById('pseudo');
                    const suggestionsDiv = document.getElementById('userSuggestions');
                    let debounceTimer;

                    pseudoInput.addEventListener('input', function() {
                        clearTimeout(debounceTimer);
                        const query = this.value.trim();

                        if (query.length < 2) {
                            suggestionsDiv.classList.add('hidden');
                            suggestionsDiv.innerHTML = '';
                            return;
                        }

                        debounceTimer = setTimeout(() => {
                            fetch(`{{ route('users.search') }}?q=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(users => {
                                    if (users.length === 0) {
                                        suggestionsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm text-center">Aucun utilisateur trouvé</div>';
                                        suggestionsDiv.classList.remove('hidden');
                                    } else {
                                        suggestionsDiv.innerHTML = users.map(user => `
                                            <div class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors user-suggestion" data-username="${user.UTI_NOM_UTILISATEUR}">
                                                <div class="font-bold text-gray-900">${user.UTI_PRENOM} ${user.UTI_NOM}</div>
                                                <div class="text-xs text-gray-500">@${user.UTI_NOM_UTILISATEUR}</div>
                                            </div>
                                        `).join('');
                                        suggestionsDiv.classList.remove('hidden');

                                        // Ajouter les événements de clic sur les suggestions
                                        document.querySelectorAll('.user-suggestion').forEach(item => {
                                            item.addEventListener('click', function() {
                                                pseudoInput.value = this.dataset.username;
                                                suggestionsDiv.classList.add('hidden');
                                            });
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Erreur lors de la recherche:', error);
                                    suggestionsDiv.classList.add('hidden');
                                });
                        }, 300);
                    });

                    // Fermer les suggestions quand on clique ailleurs
                    document.addEventListener('click', function(e) {
                        if (!pseudoInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                            suggestionsDiv.classList.add('hidden');
                        }
                    });
                </script>

                <div class="mt-6 p-4 bg-white bg-opacity-60 rounded-xl border border-blue-200">
                    <h4 class="text-xs font-bold text-gray-600 uppercase mb-2">Informations</h4>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Maximum {{ $course->COU_PARTICIPANT_PAR_EQUIPE_MAX }} participants par équipe</li>
                        <li>• Le chef compte comme participant s'il coche "Participer"</li>
                        <li>• Les membres doivent avoir un numéro de licence</li>
                    </ul>
                </div>
            </div>
            @else
            {{-- Vue pour les membres non-chefs --}}
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 flex items-center justify-center">
                <div class="text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="font-bold">Seul le chef d'équipe peut ajouter des participants</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection