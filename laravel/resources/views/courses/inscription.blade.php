@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-12 p-6">
    
    {{-- Bouton Retour --}}
    <a href="{{ route('raids.courses', $course->RAI_ID) }}" class="inline-flex items-center text-gray-500 font-bold mb-8 hover:text-black transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Retour aux courses
    </a>

    <div class="text-center mb-10">
        <h1 class="text-4xl font-black text-gray-900 uppercase mb-2">Inscription : {{ $course->COU_NOM }}</h1>
        <p class="text-gray-500">Validation de votre participation.</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-10 rounded-3xl shadow-2xl border-2 border-transparent hover:border-black transition-all duration-300 relative overflow-hidden group">
            
            <div class="absolute top-0 right-0 bg-yellow-400 text-black text-xs font-bold px-4 py-2 rounded-bl-xl uppercase tracking-widest">
                Chef d'équipe
            </div>
            
            <div class="mb-8 text-yellow-500 flex justify-center transform group-hover:scale-110 transition-transform duration-300">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <h2 class="text-3xl font-black text-center mb-6 uppercase text-gray-900">Confirmer ma participation</h2>

        <form action="{{ route('courses.team.create', [$course->RAI_ID, $course->COU_ID]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4 mb-6 text-left">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nom de l'équipe</label>
                    <input type="text" name="EQU_NOM" required placeholder="Ex: Les Vikings Rapides" 
                        class="w-full p-3 bg-gray-50 rounded-xl border border-gray-200 focus:border-black outline-none font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Blason / Logo (Optionnel)</label>
                    <input type="file" name="EQU_IMAGE" accept="image/*"
                        class="w-full p-2 bg-gray-50 rounded-xl border border-gray-200 text-sm">
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-black text-white text-lg font-black rounded-2xl hover:bg-green-600 transition-all shadow-xl transform hover:-translate-y-1 uppercase tracking-widest">
                CRÉER MON ÉQUIPE
            </button>
        </form>
            
            <p class="text-center text-xs text-gray-400 mt-4 italic">
                En cliquant, vous acceptez le règlement de la course.
            </p>
        </div>
    </div>

</div>
@endsection