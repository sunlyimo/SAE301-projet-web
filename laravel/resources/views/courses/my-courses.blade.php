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
        <h2 class="text-4xl font-black text-gray-800 tracking-tight uppercase leading-none">
            Mes Courses
        </h2>
        <p class="text-gray-500 mt-2">Gérez les courses dont vous êtes responsable.</p>
    </div>

    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800">{{ $course->COU_NOM }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                Course #{{ $course->COU_ID }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><strong>Raid :</strong> {{ $course->raid ? $course->raid->RAI_NOM : 'N/A' }}</p>
                            <p><strong>Type :</strong> {{ $course->type ? $course->type->TYP_DESCRIPTION : 'N/A' }}</p>
                            <p><strong>Lieu :</strong> {{ $course->COU_LIEU }}</p>
                            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($course->COU_DATE_DEBUT)->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('courses.edit', [$course->RAI_ID, $course->COU_ID]) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>

                            <a href="{{ route('courses.manage-teams', [$course->RAI_ID, $course->COU_ID]) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Gérer les Équipes
                            </a>

                            @if($course->COU_DATE_FIN && \Carbon\Carbon::parse($course->COU_DATE_FIN)->isPast())
                                <a href="{{ route('resultats.index', [$course->RAI_ID, $course->COU_ID]) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    Classement
                                </a>
                            @endif

                            <form action="{{ route('courses.destroy', [$course->RAI_ID, $course->COU_ID]) }}" method="POST" class="inline-block w-full"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
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
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune course trouvée</h3>
            <p class="mt-2 text-gray-500">Vous n'êtes responsable d'aucune course pour le moment.</p>
        </div>
    @endif
</div>
@endsection