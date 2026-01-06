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

        <div>
            <label class="block text-sm font-semibold text-gray-700">Mot de passe<span class="text-red-500">*</span></label></label>
            <input type="password" name="UTI_MOT_DE_PASSE" required
                class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_MOT_DE_PASSE') border-red-500 @else border-gray-200 @enderror">
            @error('UTI_MOT_DE_PASSE')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        @php
        $licenceActive = old('UTI_LICENCE') ? true : false;
        $selectedClub = old('UTI_CLUB') ?? '';
        @endphp

        <div class="flex items-center justify-between mt-4">
            <span class="text-sm font-semibold text-gray-700">Renseigner votre licence ?</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="toggle-licence" class="sr-only peer" onchange="
                    const f = document.getElementById('licence-field');
                    f.classList.toggle('hidden', !this.checked);
                    const inputs = f.querySelectorAll('input, select');
                    if (this.checked) inputs.forEach(el => el.setAttribute('required',''));
                    else {
                        inputs.forEach(el => {
                            el.removeAttribute('required');
                            el.value='';
                        });
                    }
                " {{ $licenceActive ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-500 transition-colors"></div>
                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5"></div>
            </label>
        </div>


        <div id="licence-field" class="{{ $licenceActive ? '' : 'hidden' }} mt-2 space-y-2">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Club</label>
                <select name="CLU_ID" class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_CLUB') border-red-500 @else border-gray-200 @enderror">
                    <option value="">-- Choisissez un club --</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->CLU_ID }}" {{ $selectedClub == $club->CLU_ID ? 'selected' : '' }}>{{ $club->CLU_NOM . ' (' . $club->UTI_VILLE . ')' }}</option>
                    @endforeach
                </select>
                @error('CLU_ID')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Licence</label>
                <input type="text" name="UTI_LICENCE" value="{{ old('UTI_LICENCE') }}"
                       class="w-full mt-1 p-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_LICENCE') border-red-500 @else border-gray-200 @enderror">
                @error('UTI_LICENCE')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-green-600 shadow-lg hover:shadow-green-200 transition-all active:scale-95">
            Finaliser l'inscription
        </button>
    </form>
</div>
@endsection
