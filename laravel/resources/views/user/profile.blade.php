@extends('layouts.app')

@section('content')
<div class="h-14"></div>
<div class="max-w-6xl mx-auto px-6 mt-10 mb-16">

    @if(session('success'))
        <div id="success-message" class="fixed top-10 right-10 z-50 bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl animate-fade-in">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('success-message');
                if(msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

        @php
            $licenceActive = old('UTI_LICENCE') || $user->UTI_LICENCE ? true : false;
            $selectedClub = old('CLU_ID') ?? ($user->coureur->CLU_ID ?? '');
        @endphp

        <form action="{{ route('user.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-6 mb-16"
                 x-data="{
                    editing: {{ $errors->has('UTI_NOM_UTILISATEUR') ? 'true' : 'false' }},
                    name: '{{ old('UTI_NOM_UTILISATEUR', $user->UTI_NOM_UTILISATEUR) }}'
                 }">

                {{-- Avatar --}}
                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 shadow-inner flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ $user->UTI_NOM_UTILISATEUR }}&size=128&background=random"
                         alt="Avatar">
                </div>

                {{-- Nom d'utilisateur --}}
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        {{-- Affichage du nom --}}
                        <template x-if="!editing">
                            <h1 class="text-4xl font-black text-gray-900 tracking-tight" x-text="name"></h1>
                        </template>

                        {{-- Input pour modifier --}}
                        <template x-if="editing">
                            <input type="text" x-model="name" name="UTI_NOM_UTILISATEUR"
                                   class="text-4xl font-black text-gray-900 tracking-tight border-b-2 border-gray-400 focus:outline-none"
                                   style="background: transparent; padding: 0; margin: 0; line-height: 1;"
                                   placeholder="Nom d'utilisateur">
                        </template>

                        {{-- Bouton crayon / valider --}}
                        <button type="button" @click="editing = !editing"
                                class="text-black hover:text-green-600 transition-colors p-1 cursor-pointer">
                            <svg x-show="!editing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <svg x-show="editing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Message d'erreur --}}
                    @error('UTI_NOM_UTILISATEUR')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>




            {{-- Informations personnelles --}}
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes informations</h2>
                <div class="border-t border-gray-100 pt-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">

                        {{-- Nom --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Nom
                                <button type="button" onclick="toggleField('UTI_NOM')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="text" id="UTI_NOM" name="UTI_NOM" value="{{ old('UTI_NOM', $user->UTI_NOM) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_NOM') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Prénom --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Prénom
                                <button type="button" onclick="toggleField('UTI_PRENOM')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="text" id="UTI_PRENOM" name="UTI_PRENOM" value="{{ old('UTI_PRENOM', $user->UTI_PRENOM) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_PRENOM') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Date de naissance --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide">Date de naissance</label>
                            <input type="text" value="{{ $user->UTI_DATE_NAISSANCE->format('d/m/Y') }}" disabled
                                   class="w-full p-4 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                        </div>

                    </div>
                </div>
            </div>

            {{-- Coordonnées --}}
            <div class="mb-12 pt-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Coordonnées</h2>
                <div class="border-t border-gray-100 pt-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">

                        {{-- Email --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Email
                                <button type="button" onclick="toggleField('UTI_EMAIL')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="email" id="UTI_EMAIL" name="UTI_EMAIL" value="{{ old('UTI_EMAIL', $user->UTI_EMAIL) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_EMAIL') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Rue --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Rue
                                <button type="button" onclick="toggleField('UTI_RUE')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="text" id="UTI_RUE" name="UTI_RUE" value="{{ old('UTI_RUE', $user->UTI_RUE) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_RUE') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Code postal --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Code Postal
                                <button type="button" onclick="toggleField('UTI_CODE_POSTAL')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="text" id="UTI_CODE_POSTAL" name="UTI_CODE_POSTAL" value="{{ old('UTI_CODE_POSTAL', $user->UTI_CODE_POSTAL) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_CODE_POSTAL') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Ville --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Ville
                                <button type="button" onclick="toggleField('UTI_VILLE')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="text" id="UTI_VILLE" name="UTI_VILLE" value="{{ old('UTI_VILLE', $user->UTI_VILLE) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_VILLE') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Téléphone --}}
                        <div class="flex flex-col gap-2 group">
                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                                Téléphone
                                <button type="button" onclick="toggleField('UTI_TELEPHONE')" class="text-black hover:text-green-600 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </label>
                            <input type="text" id="UTI_TELEPHONE" name="UTI_TELEPHONE" value="{{ old('UTI_TELEPHONE', $user->UTI_TELEPHONE) }}" readonly
                                   class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                            @error('UTI_TELEPHONE') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>


            <div x-data="{
                    licenceActive: {{ $licenceActive ? 'true' : 'false' }},
                    removeLicence: false
                }"
                class="mb-12 pt-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Licence & Club</h2>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-gray-700">
                        Renseigner votre licence ?
                    </span>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            class="sr-only peer"
                            x-model="licenceActive"
                            @change="
                                if (!licenceActive) {
                                    $refs.licence.value = '';
                                    $refs.club.value = '';
                                    removeLicence = true;
                                } else removeLicence = false;
                        ">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-500 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5"></div>
                    </label>
                </div>

                <div x-show="licenceActive" x-transition class="space-y-4">

                    {{-- Club --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Club</label>
                        <select
                            name="CLU_ID"
                            x-ref="club"
                            :required="licenceActive"
                            class="w-full mt-1 p-3 bg-gray-50 border rounded-xl cursor-pointer">
                            <option value="">-- Choisissez un club --</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->CLU_ID }}"
                                    {{ $selectedClub == $club->CLU_ID ? 'selected' : '' }}>
                                    {{ $club->CLU_NOM }} ({{ $club->CLU_VILLE }})
                                </option>
                            @endforeach
                        </select>
                        @error('CLU_ID')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Licence --}}
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Licence</label>
                        <button type="button" onclick="toggleField('UTI_LICENCE')" class="absolute top-0 right-0 text-blacktext-black hover:text-green-600 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </button>
                        <input
                            type="text"
                            id="UTI_LICENCE"
                            name="UTI_LICENCE"
                            x-ref="licence"
                            :required="licenceActive"
                            value="{{ old('UTI_LICENCE', $user->UTI_LICENCE) }}" readonly
                            class="w-full mt-1 p-3 bg-gray-50 border rounded-xl">
                        @error('UTI_LICENCE')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Champ caché pour le controller --}}
                <input type="hidden" name="remove_licence" :value="removeLicence ? 1 : 0">
            </div>





            <div class="mb-12 pt-4" x-data="{
    changePassword: {{ $errors->hasAny(['current_password', 'new_password', 'new_password_confirmation']) ? 'true' : 'false' }},
    resetFields() {
        $refs.current.value = '';
        $refs.new.value = '';
        $refs.confirm.value = '';
    }
}">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Mot de passe</h2>

                <button type="button"
                        @click="
                changePassword = !changePassword;
                if(!changePassword) resetFields();
            "
                        class="mb-4 px-6 py-3 bg-black text-white rounded-xl shadow hover:bg-green-600 transition-colors cursor-pointer">
                    Changer le mot de passe
                </button>

                <div x-show="changePassword" x-transition class="flex flex-col gap-4 md:w-1/2">
                    {{-- Ancien mot de passe --}}
                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Ancien mot de passe</label>
                        <input type="password" name="current_password" x-ref="current"
                               :required="changePassword"
                               :type="show ? 'text' : 'password'"
                               class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                               placeholder="Entrez votre ancien mot de passe">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer"
                                aria-label="Afficher / masquer le mot de passe">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5 c4.477
                          0 8.268 2.943 9.542 7 -1.274 4.057-5.065 7-9.542
                          7 -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19 c-4.478
                          0-8.269-2.943-9.543-7 a9.956 9.956 0 012.18-3.568M6.223
                          6.223 A9.955 9.955 0 0112 5 c4.478 0 8.269 2.943 9.543
                          7 a9.978 9.978 0 01-4.132 5.411M15 12 a3 3 0 00-3-3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            </svg>
                        </button>
                        @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Nouveau mot de passe --}}
                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Nouveau mot de passe</label>
                        <input type="password" name="new_password" x-ref="new"
                               :required="changePassword"
                               :type="show ? 'text' : 'password'"
                               class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                               placeholder="Entrez votre nouveau mot de passe">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer"
                                aria-label="Afficher / masquer le mot de passe">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5 c4.477
                          0 8.268 2.943 9.542 7 -1.274 4.057-5.065 7-9.542
                          7 -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19 c-4.478
                          0-8.269-2.943-9.543-7 a9.956 9.956 0 012.18-3.568M6.223
                          6.223 A9.955 9.955 0 0112 5 c4.478 0 8.269 2.943 9.543
                          7 a9.978 9.978 0 01-4.132 5.411M15 12 a3 3 0 00-3-3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            </svg>
                        </button>
                        @error('new_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirmation du nouveau mot de passe --}}
                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="new_password_confirmation" x-ref="confirm"
                               :required="changePassword"
                               :type="show ? 'text' : 'password'"
                               class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                               placeholder="Confirmez le nouveau mot de passe">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer"
                                aria-label="Afficher / masquer le mot de passe">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5 c4.477
                          0 8.268 2.943 9.542 7 -1.274 4.057-5.065 7-9.542
                          7 -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19 c-4.478
                          0-8.269-2.943-9.543-7 a9.956 9.956 0 012.18-3.568M6.223
                          6.223 A9.955 9.955 0 0112 5 c4.478 0 8.269 2.943 9.543
                          7 a9.978 9.978 0 01-4.132 5.411M15 12 a3 3 0 00-3-3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            </svg>
                        </button>
                        @error('new_password_confirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>


            <div class="mt-16 flex justify-center">
                <button type="submit" class="bg-black font-bold text-white px-16 py-4 rounded-xl text-lg shadow-xl hover:bg-green-600 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-tighter cursor-pointer">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
    <div class="mt-24 border-t border-gray-100 pt-12" id="historique">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 uppercase tracking-tight flex items-center justify-center gap-2">
            Historique des Courses
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($uniqueCourses as $course)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all hover:-translate-y-1">
                    {{-- Badge Type --}}
                    <div class="mb-3">
                         <span class="text-[10px] font-bold uppercase text-gray-500">
                            {{ $course->type->TYP_LIBELLE ?? 'Course' }}
                        </span>
                    </div>

                    {{-- Nom & Raid --}}
                    <h3 class="font-black text-gray-900 uppercase text-lg leading-tight truncate" title="{{ $course->COU_NOM }}">
                        {{ $course->COU_NOM }}
                    </h3>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1 mb-4 truncate">
                        {{ $course->raid->RAI_NOM }}
                    </p>

                    {{-- Infos Minimales --}}
                    <div class="flex justify-between items-end border-t border-gray-100 pt-3">
                        <div class="text-xs text-gray-500 font-bold">
                             <span class="block text-[10px] uppercase text-gray-300">Date</span>
                             {{ \Carbon\Carbon::parse($course->COU_DATE_DEBUT)->format('d/m/Y') }}
                             <span class="block text-[10px] uppercase text-gray-300">Lieu</span>
                             {{ Str::limit($course->COU_LIEU, 50) }}
                        </div>
                    </div>
                    <div class="mt-auto mb-4 py-2">
                        <a href="{{ route('raids.courses', $course->RAI_ID) }}"
                           class="block w-full text-center bg-gray-100 text-gray-800 py-2 rounded-lg font-bold text-xs hover:bg-black hover:text-white transition-colors uppercase tracking-wide">
                            Voir la course
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold text-sm italic">Aucune course terminée.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-16 border-t border-gray-100 pt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 uppercase tracking-tight flex items-center justify-center gap-2">
            Mes Équipes
        </h2>

        <div class="flex flex-wrap justify-center gap-6 mb-10">
            @forelse($allTeams as $equipe)
                @php
                    $isChef = ($equipe->UTI_ID == Auth::id());
                @endphp

                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] max-w-sm bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex flex-col h-full">

                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                            <span class="text-xl">🚩</span>
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="font-black text-gray-900 uppercase text-md truncate" title="{{ $equipe->EQU_NOM }}">
                                {{ $equipe->EQU_NOM }}
                            </h3>
                            @if($isChef)
                                <span class="text-[10px] font-bold uppercase text-yellow-600 bg-yellow-50 px-1 rounded">👑 Chef d'équipe</span>
                            @else
                                <span class="text-[10px] font-bold uppercase text-blue-600 bg-blue-50 px-1 rounded">👤 Membre</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4 flex-grow">
                        <p class="text-xs text-gray-400 uppercase font-bold">Participation à :</p>
                        <p class="text-sm font-bold text-gray-800 truncate" title="{{ optional($equipe->course)->COU_NOM ?? '—' }}">
                            {{ optional($equipe->course)->COU_NOM ?? '—' }}
                        </p>
                    </div>

                    <div class="mt-auto grid grid-cols-2 gap-2">

                        @php $raiId = $equipe->RAI_ID ?? optional($equipe->course)->RAI_ID; @endphp

                        @if($raiId)
                            <a href="{{ route('raids.courses', $raiId) }}"
                               class="flex items-center justify-center bg-gray-100 text-gray-600 py-2 rounded-lg font-bold text-xs hover:bg-gray-200 transition-colors uppercase tracking-wide"
                               title="Voir la fiche de la course">
                                Course
                            </a>
                        @else
                            <span
                                class="flex items-center justify-center bg-gray-100 text-gray-400 py-2 rounded-lg font-bold text-xs uppercase tracking-wide cursor-not-allowed"
                                title="Course indisponible">
                                Course
                            </span>
                        @endif

                        <a href="{{ route('teams.show', [$equipe->RAI_ID, $equipe->COU_ID, $equipe->EQU_ID]) }}"
                        class="flex items-center justify-center bg-black text-white py-2 rounded-lg font-bold text-xs hover:bg-green-600 transition-colors uppercase tracking-wide">
                            Équipe
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold text-sm italic">Aucune équipe rejointe.</p>
                </div>
            @endforelse
        </div>
    </div>

<script>
    function toggleField(id) {
        const input = document.getElementById(id);

        const isReadonly = input.hasAttribute('readonly');

        if (isReadonly) {
            input.removeAttribute('readonly');
            input.style.pointerEvents = 'auto';
            input.style.cursor = 'text';
            input.classList.remove('bg-gray-50');
            input.classList.add('bg-white', 'border-gray-400');
            input.focus();
        } else {
            input.setAttribute('readonly', true);
            input.style.pointerEvents = 'none';
            input.style.cursor = 'default';
            input.classList.remove('bg-white', 'border-gray-400');
            input.classList.add('bg-gray-50');
        }
    }
</script>
    <style>
        input[readonly] {
            pointer-events: none;
            user-select: none;
        }
    </style>
@endsection
