@extends('layouts.app')

@section('content')
    <div class="h-14"></div>
    <div class="max-w-6xl mx-auto px-6 mt-20 mb-16">

        @if(session('success'))
            <div id="success-message" class="fixed top-10 right-10 z-50 bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl animate-fade-in">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                    const msg = document.getElementById('success-message');
                    if(msg) { msg.style.opacity = '0'; setTimeout(() => msg.remove(), 500); }
                }, 5000);
            </script>
        @endif

        @php
            $licenceActive = old('UTI_LICENCE') || $user->UTI_LICENCE ? true : false;
            $selectedClub = old('CLU_ID', $user->CLU_ID ?? '');
        @endphp

        <form action="{{ route('user.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-8 mb-16">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 shadow-inner flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ $user->UTI_NOM_UTILISATEUR }}&size=128&background=random" alt="Avatar">
                </div>
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ $user->UTI_NOM_UTILISATEUR }}</h1>
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
                                <button type="button" onclick="enableField('UTI_NOM')" class="text-black hover:text-green-600 transition-colors">
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
                                <button type="button" onclick="enableField('UTI_PRENOM')" class="text-black hover:text-green-600 transition-colors">
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
                                <button type="button" onclick="enableField('UTI_EMAIL')" class="text-black hover:text-green-600 transition-colors">
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
                                <button type="button" onclick="enableField('UTI_RUE')" class="text-black hover:text-green-600 transition-colors">
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
                                <button type="button" onclick="enableField('UTI_CODE_POSTAL')" class="text-black hover:text-green-600 transition-colors">
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
                                <button type="button" onclick="enableField('UTI_VILLE')" class="text-black hover:text-green-600 transition-colors">
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
                                <button type="button" onclick="enableField('UTI_TELEPHONE')" class="text-black hover:text-green-600 transition-colors">
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

            {{-- Licence & Club --}}
            <div class="mb-12 pt-4" x-data="{ licenceActive: {{ $licenceActive ? 'true' : 'false' }} }">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Licence & Club</h2>

                @if(!$user->UTI_LICENCE)
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-700">Renseigner votre licence ?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="licenceActive" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-500 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5"></div>
                        </label>
                    </div>
                @endif

                <div x-show="licenceActive" x-transition class="space-y-4">
                    {{-- Club --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Club</label>
                        <select name="CLU_ID" :required="licenceActive ? true : false"
                                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all
                        @error('CLU_ID') border-red-500 @else border-gray-200 @enderror">
                            <option value="">-- Choisissez un club --</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->CLU_ID }}" {{ $selectedClub == $club->CLU_ID ? 'selected' : '' }}>
                                    {{ $club->CLU_NOM . ' (' . $club->UTI_VILLE . ')' }}
                                </option>
                            @endforeach
                        </select>
                        @error('CLU_ID') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Licence --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Licence</label>
                        <input type="text" name="UTI_LICENCE" value="{{ old('UTI_LICENCE', $user->UTI_LICENCE) }}"
                               :required="licenceActive ? true : false"
                               class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all
                        @error('UTI_LICENCE') border-red-500 @else border-gray-200 @enderror">
                        @error('UTI_LICENCE') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Mot de passe --}}
            <div class="mb-12 pt-4" x-data="{ changePassword: false }">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Mot de passe</h2>

                {{-- Bouton pour afficher/masquer le formulaire de changement --}}
                <button type="button"
                        @click="changePassword = !changePassword;
                     if(!changePassword) {
                         $refs.current.value='';
                         $refs.new.value='';
                         $refs.confirm.value='';
                     }"
                        class="mb-4 px-6 py-3 bg-black text-white rounded-xl shadow hover:bg-green-600 transition-colors">
                    Changer le mot de passe
                </button>

                {{-- Formulaire de changement de mot de passe --}}
                <div x-show="changePassword" x-transition class="flex flex-col gap-4 md:w-1/2">

                    {{-- Ancien mot de passe --}}
                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Ancien mot de passe</label>
                        <input type="password" name="current_password" x-ref="current"
                               :type="show ? 'text' : 'password'"
                               class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                               placeholder="Entrez votre ancien mot de passe">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Afficher / masquer le mot de passe">
                            <!-- Œil ouvert -->
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

                            <!-- Œil barré -->
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
                    </div>

                    {{-- Nouveau mot de passe --}}
                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Nouveau mot de passe</label>
                        <input type="password" name="new_password" x-ref="new"
                               :type="show ? 'text' : 'password'"
                               class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                               placeholder="Entrez votre nouveau mot de passe">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Afficher / masquer le mot de passe">
                            <!-- Œil ouvert -->
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

                            <!-- Œil barré -->
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
                    </div>

                    {{-- Confirmation du nouveau mot de passe --}}
                    <div x-data="{ show: false }" class="relative">
                        <label class="block text-sm font-semibold text-gray-700">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="new_password_confirmation" x-ref="confirm"
                               :type="show ? 'text' : 'password'"
                               class="w-full p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                               placeholder="Confirmez le nouveau mot de passe">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Afficher / masquer le mot de passe">
                            <!-- Œil ouvert -->
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

                            <!-- Œil barré -->
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
                    </div>
                </div>
            </div>





            <div class="mt-16 flex justify-center">
                <button type="submit" class="bg-black font-bold text-white px-16 py-4 rounded-xl text-lg shadow-xl hover:bg-green-600 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-tighter">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <script>
        function enableField(id) {
            const input = document.getElementById(id);
            input.removeAttribute('readonly');
            input.style.pointerEvents = 'auto';
            input.style.cursor = 'text';
            input.classList.remove('bg-gray-50');
            input.classList.add('bg-white');
            input.focus();
        }
    </script>
    <style>
        input[readonly] {
            pointer-events: none;
            user-select: none;
        }
    </style>
@endsection
