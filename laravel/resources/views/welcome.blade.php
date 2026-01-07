@extends('layouts.app')

@section('title', 'Bienvenue')

@section('content')

<!-- Hero Section avec image de fond -->
<div class="relative min-h-[500px] overflow-hidden">
    <img src="{{ asset('images/foret.jpg') }}" alt="Forêt" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
    
    <div class="relative z-10 flex flex-col items-center justify-center h-full min-h-[500px] text-center px-4">
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-4 tracking-tight">
            Bienvenue 
            @auth
                <span class="text-green-400">{{ Auth::user()->UTI_NOM_UTILISATEUR }}</span>
            @endauth
        </h1>
        <p class="text-xl md:text-2xl text-white/90 font-light max-w-3xl">
            Votre plateforme dédiée aux raids et courses d'orientation
        </p>
        
        @guest
        <div class="mt-8 flex gap-4">
            <a href="/register" class="px-8 py-4 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-all shadow-xl hover:shadow-2xl">
                Créer un compte
            </a>
            <a href="/login" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 rounded-xl font-bold hover:bg-white/20 transition-all">
                Se connecter
            </a>
        </div>
        @endguest
    </div>
</div>

<!-- Section Raids à venir -->
<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de section -->
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold uppercase tracking-wider mb-4">
                Prochains événements
            </span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                Les Raids à Venir
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Découvrez les prochains raids et inscrivez-vous dès maintenant aux courses qui vous passionnent
            </p>
        </div>

        <!-- Grille des raids -->
        <div class="flex flex-wrap justify-center gap-6 mb-8">
            @forelse ($raids->take(4) as $raid)
                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col">
                    
                    <!-- Badge de statut -->
                    <div class="p-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                        @php $now = now(); @endphp
                        <div class="flex justify-between items-start mb-3">
                            @if($raid->RAI_INSCRI_DATE_DEBUT <= $now && $raid->RAI_INSCRI_DATE_FIN >= $now)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">
                                    <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                    Ouvert
                                </span>
                            @elseif($raid->RAI_INSCRI_DATE_DEBUT > $now)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white">
                                    <span class="w-2 h-2 bg-white rounded-full mr-2"></span>
                                    Bientôt
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-400 text-white">
                                    Terminé
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

                    <!-- Informations détaillées -->
                    <div class="p-6 flex-grow space-y-4">
                        <!-- Dates de l'événement -->
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

                        <!-- Dates d'inscription -->
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

                    <!-- Bouton d'action -->
                    <div class="p-6 pt-0">
                        <a href="{{ route('raids.courses', $raid->RAI_ID) }}" 
                           class="block w-full py-3 bg-gradient-to-r from-black to-gray-800 text-white text-center rounded-xl font-bold text-sm hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex justify-center items-center">
                            Voir les courses
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xl text-gray-400 font-bold">Aucun raid disponible pour le moment</p>
                </div>
            @endforelse
        </div>

        <!-- Bouton voir tous les raids -->
        @if($raids->count() > 4)
        <div class="text-center">
            <a href="{{ route('raids.index') }}" class="inline-flex items-center px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 rounded-xl font-bold hover:border-green-600 hover:text-green-600 transition-all shadow-md hover:shadow-lg">
                Voir tous les raids
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Section Description / Fonctionnalités -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Texte à gauche -->
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                    Tout pour Gérer Vos Courses d'Orientation
                </h2>
                <p class="text-lg text-gray-600 mb-8">
                    CartoRun est votre solution tout-en-un pour gérer facilement vos inscriptions et suivre vos performances dans les raids et courses d'orientation.
                </p>

                <!-- Liste des fonctionnalités -->
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Inscriptions simplifiées</h3>
                            <p class="text-gray-600">Découvrez et inscrivez-vous aux raids et courses en quelques clics</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Gestion d'équipes</h3>
                            <p class="text-gray-600">Créez et gérez vos équipes, participants et documents facilement</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Suivi des performances</h3>
                            <p class="text-gray-600">Consultez vos résultats et classements en temps réel</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Outils pour organisateurs</h3>
                            <p class="text-gray-600">Gérez les inscriptions, dossards et résultats de vos événements</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo à droite -->
            <div class="flex justify-center lg:justify-end">
                <div class="relative">
                    <div class="absolute inset-0 bg-green-200 rounded-full blur-3xl opacity-20 animate-pulse"></div>
                    <img src="{{ asset('images/logo.png') }}" alt="CartoRun Logo" class="relative w-80 h-auto drop-shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection