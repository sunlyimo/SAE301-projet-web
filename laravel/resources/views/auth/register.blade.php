@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 p-8 bg-white shadow-2xl rounded-3xl border border-gray-100">
    <h2 class="text-3xl font-bold mb-8 text-black tracking-tight">Créer un compte</h2>
    
    <form method="POST" action="/register" class="space-y-5">
        @csrf
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Nom</label>
                <input type="text" name="UTI_NOM" value="{{ old('UTI_NOM') }}" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Prénom</label>
                <input type="text" name="UTI_PRENOM" value="{{ old('UTI_PRENOM') }}" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Nom d'utilisateur (Pseudo)</label>
            <input type="text" name="UTI_NOM_UTILISATEUR" value="{{ old('UTI_NOM_UTILISATEUR') }}" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all @error('UTI_NOM_UTILISATEUR') border-red-500 @enderror">
            @error('UTI_NOM_UTILISATEUR')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Email</label>
            <input type="email" name="UTI_EMAIL" value="{{ old('UTI_EMAIL') }}" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Date de naissance</label>
            <input type="date" name="UTI_DATE_NAISSANCE" value="{{ old('UTI_DATE_NAISSANCE') }}" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Mot de passe</label>
            <input type="password" name="UTI_MOT_DE_PASSE" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Téléphone</label>
                <input type="tel" name="UTI_TELEPHONE" value="{{ old('UTI_TELEPHONE') }}" class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Adresse</label>
                <input type="text" name="UTI_ADRESSE" value="{{ old('UTI_ADRESSE') }}" class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
        </div>

        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-blue-600 shadow-lg transition-all active:scale-95">
            Finaliser l'inscription
        </button>
    </form>
</div>
@endsection