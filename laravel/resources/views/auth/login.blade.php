@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-8 bg-white shadow-xl rounded-2xl border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-black">Connexion</h2>
    
    @if($errors->any())
        <div class="mb-4 p-2 bg-red-100 text-red-600 text-sm rounded">
            Identifiants incorrects.
        </div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="UTI_EMAIL" required class="w-full mt-1 p-2 border rounded-md focus:ring-blue-500 border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password" required class="w-full mt-1 p-2 border rounded-md focus:ring-blue-500 border-gray-300">
        </div>
        <button type="submit" class="w-full bg-black text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition-all">
            Se connecter
        </button>
    </form>
</div>
@endsection