@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 p-8 bg-white shadow-2xl rounded-3xl border border-gray-100">
    <h2 class="text-3xl font-bold mb-8 text-black tracking-tight">Créer un compte</h2>

    <form method="POST" action="/register" class="space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Nom<span class="text-red-500">*</span></label></label>
                <input type="text" name="UTI_NOM" value="{{ old('UTI_NOM') }}" required
                    class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_NOM') border-red-500 @else border-gray-200 @enderror">
                @error('UTI_NOM')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Prénom<span class="text-red-500">*</span></label></label>
                <input type="text" name="UTI_PRENOM" value="{{ old('UTI_PRENOM') }}" required
                    class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_PRENOM') border-red-500 @else border-gray-200 @enderror">
                @error('UTI_PRENOM')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Nom d'utilisateur (Pseudo)<span class="text-red-500">*</span></label></label>
            <input type="text" name="UTI_NOM_UTILISATEUR" value="{{ old('UTI_NOM_UTILISATEUR') }}" required
                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_NOM_UTILISATEUR') border-red-500 @else border-gray-200 @enderror">
            @error('UTI_NOM_UTILISATEUR')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Date de naissance<span class="text-red-500">*</span></label></label>
            <input type="date" name="UTI_DATE_NAISSANCE" value="{{ old('UTI_DATE_NAISSANCE') }}" required
                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_DATE_NAISSANCE') border-red-500 @else border-gray-200 @enderror">
            @error('UTI_DATE_NAISSANCE')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Email<span class="text-red-500">*</span></label></label>
            <input type="email" name="UTI_EMAIL" value="{{ old('UTI_EMAIL') }}" required
                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_EMAIL') border-red-500 @else border-gray-200 @enderror">
            @error('UTI_EMAIL')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Rue<span class="text-red-500">*</span></label></label>
            <input type="text" name="UTI_RUE" value="{{ old('UTI_RUE') }}" required
                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_RUE') border-red-500 @else border-gray-200 @enderror">
            @error('UTI_RUE')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Code Postal<span class="text-red-500">*</span></label></label>
                <input type="text" name="UTI_CODE_POSTAL" value="{{ old('UTI_CODE_POSTAL') }}" required
                    class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_CODE_POSTAL') border-red-500 @else border-gray-200 @enderror">
                @error('UTI_CODE_POSTAL')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Ville<span class="text-red-500">*</span></label></label>
                <input type="text" name="UTI_VILLE" value="{{ old('UTI_VILLE') }}" required
                    class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_VILLE') border-red-500 @else border-gray-200 @enderror">
                @error('UTI_VILLE')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Téléphone</label>
            <input type="tel" name="UTI_TELEPHONE" value="{{ old('UTI_TELEPHONE') }}"
                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_TELEPHONE') border-red-500 @else border-gray-200 @enderror">
            @error('UTI_TELEPHONE')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block text-sm font-semibold text-gray-700">
                Mot de passe <span class="text-red-500">*</span>
            </label>

            <div class="relative">
                <input
                    :type="show ? 'text' : 'password'"
                    name="UTI_MOT_DE_PASSE"
                    required
                    class="w-full mt-1 p-3 pr-12 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all
            @error('UTI_MOT_DE_PASSE') border-red-500 @else border-gray-200 @enderror"
                >

                <button
                    type="button"
                    @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                    aria-label="Afficher / masquer le mot de passe"
                >
                    <!-- Œil ouvert -->
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                       c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7
                       -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <!-- Œil barré -->
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.875 18.825A10.05 10.05 0 0112 19
                       c-4.478 0-8.269-2.943-9.543-7
                       a9.956 9.956 0 012.18-3.568M6.223 6.223
                       A9.955 9.955 0 0112 5
                       c4.478 0 8.269 2.943 9.543 7
                       a9.978 9.978 0 01-4.132 5.411M15 12
                       a3 3 0 00-3-3" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3l18 18" />
                    </svg>
                </button>
            </div>

            @error('UTI_MOT_DE_PASSE')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>


        @php
        $licenceActive = old('UTI_LICENCE') ? true : false;
        $selectedClub = old('CLU_ID') ?? '';
        @endphp

        <div x-data="{ licenceActive: {{ old('UTI_LICENCE') || old('CLU_ID') ? 'true' : 'false' }} }">
            <!-- Toggle -->
            <div class="flex items-center justify-between mt-4">
                <span class="text-sm font-semibold text-gray-700">Renseigner votre licence ?</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" x-model="licenceActive" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-500 transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5"></div>
                </label>
            </div>

            <!-- Bloc Club + Licence -->
            <div x-show="licenceActive" x-transition class="mt-2 space-y-2">
                <!-- Club -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Club</label>
                    <select
                        name="CLU_ID"
                        :required="licenceActive ? true : false"
                        class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all
                @error('UTI_CLUB') border-red-500 @else border-gray-200 @enderror"
                    >
                        <option value="">-- Choisissez un club --</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->CLU_ID }}" {{ old('CLU_ID') == $club->CLU_ID ? 'selected' : '' }}>
                                {{ $club->CLU_NOM . ' (' . $club->UTI_VILLE . ')' }}
                            </option>
                        @endforeach
                    </select>
                    @error('CLU_ID')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Licence -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Licence</label>
                    <input
                        type="text"
                        name="UTI_LICENCE"
                        value="{{ old('UTI_LICENCE') }}"
                        :required="licenceActive ? true : false"
                        class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all
                @error('UTI_LICENCE') border-red-500 @else border-gray-200 @enderror"
                    >
                    @error('UTI_LICENCE')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>


        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-green-600 shadow-lg hover:shadow-green-200 transition-all active:scale-95 cursor-pointer">
            Finaliser l'inscription
        </button>
    </form>
</div>
@endsection
