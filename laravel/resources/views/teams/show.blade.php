@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12 p-6">
    
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">Mon Équipe #{{ $equipe->EQU_ID }}</h1>
                <p class="text-gray-500">Course #{{ $equipe->COU_ID }} (Raid #{{ $equipe->RAI_ID }})</p>
            </div>
            @if($isChef)
                <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-xl font-bold text-sm">👑 Vous êtes le Chef</span>
            @else
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-xl font-bold text-sm">👤 Vous êtes Membre</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            {{-- LISTE DES MEMBRES --}}
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Composition de l'équipe
                </h3>
                <ul class="space-y-3">
                    {{-- Le Chef --}}
                    <li class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                        <div>
                            <span class="font-bold text-gray-900">{{ $equipe->chef->UTI_PRENOM }} {{ $equipe->chef->UTI_NOM }}</span>
                            <span class="block text-xs text-yellow-600 font-bold uppercase">Chef d'équipe</span>
                        </div>
                        <span class="text-xl">👑</span>
                    </li>

                    {{-- Les Membres --}}
                    @foreach($equipe->membres as $membre)
                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <span class="font-bold text-gray-900">{{ $membre->utilisateur->UTI_NOM_UTILISATEUR }}</span>
                                <span class="block text-xs text-gray-400 font-bold uppercase">Membre</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- FORMULAIRE D'AJOUT (Chef seulement) --}}
            @if($isChef)
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Ajouter un coéquipier</h3>
                <p class="text-sm text-gray-500 mb-4">Entrez le <strong>nom d'utilisateur</strong> exact du membre à inviter.</p>

                <form action="{{ route('teams.add', [$equipe->RAI_ID, $equipe->COU_ID, $equipe->EQU_ID]) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="pseudo" placeholder="Ex: Viking76" 
                            class="w-full p-4 rounded-xl border-2 border-gray-200 focus:border-black outline-none transition-colors font-bold" required>
                        
                        @error('pseudo') 
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> 
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-black text-white font-bold py-3 rounded-xl hover:bg-green-600 transition-colors">
                        AJOUTER À L'ÉQUIPE
                    </button>
                </form>

                @if(session('success'))
                    <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm font-bold text-center">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
@endsection