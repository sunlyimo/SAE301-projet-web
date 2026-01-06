@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 p-8 bg-white shadow-2xl rounded-3xl border border-gray-100">
    <h2 class="text-3xl font-bold mb-8 text-black tracking-tight">Créer un compte</h2>
    
    <form method="POST" action="/register" class="space-y-5">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Nom</label>
                <input type="text" name="UTI_NOM" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Prénom</label>
                <input type="text" name="UTI_PRENOM" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Email (Identifiant)</label>
            <input type="email" name="UTI_EMAIL" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Date de naissance</label>
            <input type="date" name="UTI_DATE_NAISSANCE" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Adresse</label>
            <input type="text" name="UTI_ADRESSE" class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Téléphone</label>
            <input type="tel" name="UTI_TELEPHONE" class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Mot de passe</label>
            <input type="password" name="password" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>

        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-blue-600 shadow-lg hover:shadow-blue-200 transition-all active:scale-95">
            Finaliser l'inscription
        </button>
    </form>
</div>
@endsection