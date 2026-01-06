@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Qui sommes-nous ?</h1>
            
            <div class="space-y-4 text-gray-700">
                <p>
                    Nous sommes une entreprise d'informatique composée de 8 développeurs engagés.
                </p>
                
                <p>
                Nous favorisons la collaboration et l’efficacité, ainsi qu’une grande réactivité et une écoute attentive face aux besoins des clients afin que votre utilisation soit la plus plaisante possible.
                Nous mettons un point d’honneur à offrir des solutions fiables et innovantes, tout en gardant une démarche écologique et responsable.                 </p>
            </div>
        </div>

        <div class="flex justify-center lg:justify-end">
            <img src="{{ asset('images/logo.png') }}" alt="CartoRun Logo" class="w-48 h-auto">
        </div>
    </div>

    <div class="mt-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-12">Nos valeurs</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Esprit d'équipe</h3>
                    <img src="{{ asset('images/logoEquipe.jpg') }}" alt="Esprit d'équipe" class="w-12 h-12 object-contain">
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">L'écoute</h3>
                    <img src="{{ asset('images/logoOreille.png') }}" alt="L'écoute" class="w-12 h-12 object-contain">
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Écologie</h3>
                    <img src="{{ asset('images/logoEcologie.png') }}" alt="Écologie" class="w-12 h-12 object-contain">
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Fiabilité</h3>
                    <img src="{{ asset('images/logoFiabilite.png') }}" alt="Fiabilité" class="w-12 h-12 object-contain">
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Réactivité</h3>
                    <img src="{{ asset('images/logoReactivite.png') }}" alt="Réactivité" class="w-12 h-12 object-contain">
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Innovation</h3>
                    <img src="{{ asset('images/logoInnovation.jpg') }}" alt="Innovation" class="w-12 h-12 object-contain">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection