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
        </div>
    </div>

    <div class="mb-10">
        <h2 class="text-4xl font-black text-gray-800 tracking-tight uppercase leading-none">
            Mes Raids
        </h2>
        <p class="text-gray-500 mt-2">Gérez vos raids : consultez les courses, modifiez les informations.</p>
    </div>

    @if($raids->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($raids as $raid)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800">{{ $raid->RAI_NOM }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                Raid #{{ $raid->RAI_ID }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><strong>Lieu :</strong> {{ $raid->RAI_LIEU }}</p>
                            <p><strong>Dates :</strong> {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN)->format('d/m/Y') }}</p>
                            <p><strong>Inscriptions :</strong> {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_DEBUT)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_FIN)->format('d/m/Y') }}</p>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('courses.create') }}?raid_id={{ $raid->RAI_ID }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Ajouter une Course
                            </a>

                            <a href="{{ route('raids.courses', $raid->RAI_ID) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Voir les Courses
                            </a>

                            <a href="{{ route('raids.edit', $raid->RAI_ID) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier le Raid
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun raid trouvé</h3>
            <p class="mt-2 text-gray-500">Vous n'êtes responsable d'aucun raid pour le moment.</p>
            <div class="mt-6">
                <a href="{{ route('raids.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer un Raid
                </a>
            </div>
        </div>
    @endif
</div>
@endsection