@extends('layouts.app')

@section('content')
<div class="max-w-[90rem] mx-auto my-12 p-6">
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-4xl font-black text-gray-800 tracking-tight uppercase">Mes Raids</h1>
            <p class="text-gray-500 mt-2">Gérez vos raids : consultez les courses, modifiez les informations.</p>
        </div>
    </div>

    @if($raids->count() > 0)
        <div class="flex flex-wrap justify-center gap-6 mb-8">
            @foreach($raids as $raid)
                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col">
                    <div class="p-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                        @php $now = now(); @endphp
                        <div class="flex justify-between items-start mb-3">
                            @if($raid->RAI_INSCRI_DATE_DEBUT <= $now && $raid->RAI_INSCRI_DATE_FIN >= $now)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">
                                    Inscriptions ouvertes
                                </span>
                            @elseif($raid->RAI_INSCRI_DATE_DEBUT > $now)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white">
                                    Inscriptions non ouvertes
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-400 text-white">
                                    Inscriptions terminées
                                </span>
                            @endif
                            <span class="text-gray-300 font-black text-sm">#{{ $raid->RAI_ID }}</span>
                        </div>

                        <h3 class="text-xl font-black text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">
                            {{ $raid->RAI_NOM }}
                        </h3>
                        <div class="flex items-center text-sm text-gray-600 mb-1">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $raid->RAI_LIEU }}
                        </div>
                        
                        <div class="flex items-center text-sm font-bold text-gray-900">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $raid->total_course }} {{ $raid->total_course > 1 ? 'courses' : 'course' }}
                        </div>
                    </div>
                    <div class="p-6 flex-grow space-y-4">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <div class="flex items-center text-xs text-blue-700 font-bold uppercase mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Événement
                            </div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT)->format('d/m/Y') }}
                                <span class="text-gray-400">→</span>
                                {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN)->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                            <div class="flex items-center text-xs text-purple-700 font-bold uppercase mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Inscriptions
                            </div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_DEBUT)->format('d/m/Y') }} 
                                <span class="text-gray-400">→</span>
                                {{ \Carbon\Carbon::parse($raid->RAI_INSCRI_DATE_FIN)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="p-6 pt-0 mt-auto flex flex-col gap-2">
                        <a href="{{ route('raids.courses', $raid->RAI_ID) }}" 
                           class="w-full py-3 bg-gradient-to-r from-black to-gray-800 text-white text-center rounded-xl font-bold text-sm hover:from-green-600 hover:to-green-700 transition-all shadow-lg flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Gérer les Courses
                        </a>

                        <div class="flex gap-2">
                            <a href="{{ route('courses.create') }}?raid_id={{ $raid->RAI_ID }}" 
                               class="flex-1 py-3 bg-purple-100 text-purple-700 text-center rounded-xl font-bold text-sm hover:bg-purple-200 transition-colors flex justify-center items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                + Course
                            </a>
                            <a href="{{ route('raids.edit', $raid->RAI_ID) }}" 
                               class="w-12 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center hover:bg-yellow-400 hover:text-white transition-all shadow-inner border border-gray-200"
                               title="Modifier ce raid">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun raid trouvé</h3>
            <p class="mt-2 text-gray-500">Vous n'êtes responsable d'aucun raid pour le moment.</p>
            <div class="mt-6">
                <a href="{{ route('raids.create') }}" class="inline-flex items-center px-6 py-3 bg-black text-white rounded-xl font-bold hover:bg-green-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Créer un Raid
                </a>
            </div>
        </div>
    @endif
</div>
@endsection