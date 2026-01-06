@extends('layouts.app')

@section('title', 'Créer un Raid')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Créer un nouveau raid</h1>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Erreurs de validation:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('raids.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom du Raid -->
                    <div class="md:col-span-2">
                        <label for="RAI_NOM" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom du Raid <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="RAI_NOM" id="RAI_NOM" value="{{ old('RAI_NOM') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Club -->
                    <div>
                        <label for="CLU_ID" class="block text-sm font-semibold text-gray-700 mb-2">
                            Club <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="hidden" name="CLU_ID" id="CLU_ID" value="{{ old('CLU_ID') }}" required>
                            <input type="text" id="club_search" placeholder="Rechercher un club..." autocomplete="off"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <div id="club_selected" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900" id="club_name"></p>
                                        <p class="text-sm text-gray-600" id="club_ville"></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="#" id="club_details_link" target="_blank" class="text-green-600 hover:text-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                            </svg>
                                        </a>
                                        <button type="button" onclick="clearClubSelection()" class="text-red-600 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="club_results" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                            </div>
                        </div>
                    </div>

                    <!-- Utilisateur -->
                    <div>
                        <label for="UTI_ID" class="block text-sm font-semibold text-gray-700 mb-2">
                            Utilisateur <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="hidden" name="UTI_ID" id="UTI_ID" value="{{ old('UTI_ID') }}" required>
                            <input type="text" id="user_search" placeholder="Rechercher un utilisateur..." autocomplete="off"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <div id="user_selected" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900" id="user_name"></p>
                                        <p class="text-sm text-gray-600" id="user_email"></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="#" id="user_details_link" target="_blank" class="text-green-600 hover:text-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                            </svg>
                                        </a>
                                        <button type="button" onclick="clearUserSelection()" class="text-red-600 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="user_results" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                            </div>
                        </div>
                    </div>

                    <!-- Dates du Raid -->
                    <div>
                        <label for="RAI_RAID_DATE_DEBUT" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de début du raid <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="RAI_RAID_DATE_DEBUT" id="RAI_RAID_DATE_DEBUT" value="{{ old('RAI_RAID_DATE_DEBUT') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="RAI_RAID_DATE_FIN" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de fin du raid <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="RAI_RAID_DATE_FIN" id="RAI_RAID_DATE_FIN" value="{{ old('RAI_RAID_DATE_FIN') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Dates d'inscription -->
                    <div>
                        <label for="RAI_INSCRI_DATE_DEBUT" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de début des inscriptions <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="RAI_INSCRI_DATE_DEBUT" id="RAI_INSCRI_DATE_DEBUT" value="{{ old('RAI_INSCRI_DATE_DEBUT') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="RAI_INSCRI_DATE_FIN" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de fin des inscriptions <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="RAI_INSCRI_DATE_FIN" id="RAI_INSCRI_DATE_FIN" value="{{ old('RAI_INSCRI_DATE_FIN') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Contact et Web -->
                    <div>
                        <label for="RAI_CONTACT" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email de contact <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="RAI_CONTACT" id="RAI_CONTACT" value="{{ old('RAI_CONTACT') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="RAI_WEB" class="block text-sm font-semibold text-gray-700 mb-2">
                            Site web <span class="text-red-500">*</span>
                        </label>
                        <input type="url" name="RAI_WEB" id="RAI_WEB" value="{{ old('RAI_WEB') }}" required
                            placeholder="https://example.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Lieu -->
                    <div class="md:col-span-2">
                        <label for="RAI_LIEU" class="block text-sm font-semibold text-gray-700 mb-2">
                            Lieu <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="RAI_LIEU" id="RAI_LIEU" value="{{ old('RAI_LIEU') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label for="RAI_IMAGE" class="block text-sm font-semibold text-gray-700 mb-2">
                            Image du raid
                        </label>
                        <input type="file" name="RAI_IMAGE" id="RAI_IMAGE" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ url('/raids') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-black text-white font-bold rounded-lg hover:bg-green-600 transition-all duration-300 shadow-md active:scale-95">
                        Créer le raid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let clubSearchTimeout;
    let userSearchTimeout;

    // Club search functionality
    const clubSearchInput = document.getElementById('club_search');
    const clubResults = document.getElementById('club_results');
    const clubSelected = document.getElementById('club_selected');
    const clubIdInput = document.getElementById('CLU_ID');

    clubSearchInput.addEventListener('input', function() {
        clearTimeout(clubSearchTimeout);
        const search = this.value.trim();

        if (search.length < 2) {
            clubResults.classList.add('hidden');
            return;
        }

        clubSearchTimeout = setTimeout(() => {
            fetch(`/api/clubs/search?search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(clubs => {
                    if (clubs.length === 0) {
                        clubResults.innerHTML = '<div class="p-3 text-gray-500 text-sm">Aucun club trouvé</div>';
                    } else {
                        clubResults.innerHTML = clubs.map(club => `
                            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0" onclick="selectClub(${club.CLU_ID}, '${club.CLU_NOM}', '${club.CLU_VILLE || ''}')">
                                <p class="font-semibold text-gray-900">${club.CLU_NOM}</p>
                                ${club.CLU_VILLE ? `<p class="text-sm text-gray-600">${club.CLU_VILLE}</p>` : ''}
                            </div>
                        `).join('');
                    }
                    clubResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    clubResults.innerHTML = '<div class="p-3 text-red-500 text-sm">Erreur lors de la recherche</div>';
                    clubResults.classList.remove('hidden');
                });
        }, 300);
    });

    function selectClub(id, name, ville) {
        clubIdInput.value = id;
        document.getElementById('club_name').textContent = name;
        document.getElementById('club_ville').textContent = ville;
        document.getElementById('club_details_link').href = `/clubs/${id}`;
        clubSelected.classList.remove('hidden');
        clubSearchInput.value = '';
        clubResults.classList.add('hidden');
    }

    function clearClubSelection() {
        clubIdInput.value = '';
        clubSelected.classList.add('hidden');
    }

    // User search functionality
    const userSearchInput = document.getElementById('user_search');
    const userResults = document.getElementById('user_results');
    const userSelected = document.getElementById('user_selected');
    const userIdInput = document.getElementById('UTI_ID');

    userSearchInput.addEventListener('input', function() {
        clearTimeout(userSearchTimeout);
        const search = this.value.trim();

        if (search.length < 2) {
            userResults.classList.add('hidden');
            return;
        }

        userSearchTimeout = setTimeout(() => {
            fetch(`/api/users/search?search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(users => {
                    if (users.length === 0) {
                        userResults.innerHTML = '<div class="p-3 text-gray-500 text-sm">Aucun utilisateur trouvé</div>';
                    } else {
                        userResults.innerHTML = users.map(user => `
                            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0" onclick="selectUser(${user.id}, '${user.name}', '${user.email}')">
                                <p class="font-semibold text-gray-900">${user.name}</p>
                                <p class="text-sm text-gray-600">${user.email}</p>
                            </div>
                        `).join('');
                    }
                    userResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    userResults.innerHTML = '<div class="p-3 text-red-500 text-sm">Erreur lors de la recherche</div>';
                    userResults.classList.remove('hidden');
                });
        }, 300);
    });

    function selectUser(id, name, email) {
        userIdInput.value = id;
        document.getElementById('user_name').textContent = name;
        document.getElementById('user_email').textContent = email;
        document.getElementById('user_details_link').href = `/users/${id}`;
        userSelected.classList.remove('hidden');
        userSearchInput.value = '';
        userResults.classList.add('hidden');
    }

    function clearUserSelection() {
        userIdInput.value = '';
        userSelected.classList.add('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!clubSearchInput.contains(event.target) && !clubResults.contains(event.target)) {
            clubResults.classList.add('hidden');
        }
        if (!userSearchInput.contains(event.target) && !userResults.contains(event.target)) {
            userResults.classList.add('hidden');
        }
    });
</script>
@endsection
