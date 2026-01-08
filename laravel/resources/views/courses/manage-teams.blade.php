@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto my-12 p-6">
    <div class="flex justify-between items-center mb-10">
        <div class="w-full max-w-2xl">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-md flex justify-between items-center animate-bounce">
                    <div class="flex items-center">
                        <span class="text-xl mr-2">✅</span>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-900 font-bold hover:text-green-700">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-md flex justify-between items-center animate-bounce">
                    <div class="flex items-center">
                        <span class="text-xl mr-2">❌</span>
                        <div>
                            @foreach($errors->all() as $error)
                                <p class="font-bold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-900 font-bold hover:text-red-700">&times;</button>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-10">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('courses.my-courses') }}" class="text-gray-400 hover:text-black transition-colors flex items-center gap-1 font-bold text-sm group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour à Mes Courses
            </a>
        </div>
        <h2 class="text-4xl font-black text-gray-800 tracking-tight uppercase leading-none">
            Gestion des Équipes
        </h2>
        <p class="text-gray-500 mt-2">Course : {{ $course->COU_NOM }} - {{ $course->raid ? $course->raid->RAI_NOM : 'Raid inconnu' }}</p>
    </div>

    @if($pendingTeams->count() > 0)
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Équipes en attente de validation
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendingTeams as $team)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-800">{{ $team->EQU_NOM }}</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                        En attente
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                        Équipe #{{ $team->EQU_ID }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <p><strong>Chef d'équipe :</strong> {{ $team->chef ? $team->chef->UTI_PRENOM . ' ' . $team->chef->UTI_NOM : 'Non défini' }}</p>
                                <p><strong>Membres :</strong> {{ $team->membres ? $team->membres->count() : 0 }}</p>
                            </div>

                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('teams.show', [$course->RAI_ID, $course->COU_ID, $team->EQU_ID]) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Voir l'Équipe
                                </a>

                                <form action="{{ route('teams.validate', [$course->RAI_ID, $course->COU_ID, $team->EQU_ID]) }}" method="POST" class="inline-block w-full"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir valider cette équipe ? Cette action confirmera définitivement l\'inscription de l\'équipe à la course et validera le paiement.')">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Valider l'Équipe
                                    </button>
                                </form>

                                <form action="{{ route('teams.destroy', [$course->RAI_ID, $course->COU_ID, $team->EQU_ID]) }}" method="POST" class="inline-block w-full"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Supprimer l'Équipe
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($validatedTeams->count() > 0)
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Équipes validées (Paiement validé)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($validatedTeams as $team)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border-2 border-green-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-800">{{ $team->EQU_NOM }}</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                        Validée ✓
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                        Équipe #{{ $team->EQU_ID }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <p><strong>Chef d'équipe :</strong> {{ $team->chef ? $team->chef->UTI_PRENOM . ' ' . $team->chef->UTI_NOM : 'Non défini' }}</p>
                                <p><strong>Membres :</strong> {{ $team->membres ? $team->membres->count() : 0 }}</p>
                                <p class="text-green-600 font-semibold"><strong>✓ Paiement validé</strong></p>
                            </div>

                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('teams.show', [$course->RAI_ID, $course->COU_ID, $team->EQU_ID]) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Voir l'Équipe
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($pendingTeams->count() == 0 && $validatedTeams->count() == 0)
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune équipe trouvée</h3>
            <p class="mt-2 text-gray-500">Il n'y a pas encore d'équipes inscrites à cette course.</p>
        </div>
    @endif
</div>
@endsection