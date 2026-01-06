@extends('layouts.app')

@section('title', 'Bienvenue')

@section('content')

<div class="relative" style="background-image: url('/images/foret.png'); background-size: cover; background-position: center; min-height: 400px;">
    
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    
    
    <div class="relative z-10 flex items-center justify-center h-full min-h-[400px]">
        <h1 class="text-6xl md:text-7xl lg:text-8xl font-bold text-white">CartoRun</h1>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Bienvenue sur CartoRun</h2>
            
            <p class="text-gray-700 mb-4">
                Gérez facilement vos inscriptions et suivez vos performances dans les raids et courses d'orientation. 
                Cette plateforme vous permet de :
            </p>

            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>Découvrir et vous inscrire aux raids et courses.</li>
                <li>Créer et gérer vos équipes, vos participants et vos documents.</li>
                <li>Suivre vos résultats et vos classements.</li>
                <li>Pour les organisateurs : créer des courses, gérer les inscriptions, les dossards et les résultats.</li>
            </ul>

            <p class="text-gray-700">
                Une application simple et complète pour tous les coureurs, clubs et organisateurs d'événements d'orientation.
            </p>
        </div>

        
        <div class="flex items-center justify-center lg:justify-end">
            <img src="{{ asset('images/logo.png') }}" alt="CartoRun Logo" class="w-64 h-auto">
        </div>
    </div>
</div>
@endsection