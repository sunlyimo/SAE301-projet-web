@extends('layouts.app')

@section('content')
<div class="h-14"></div>
<div class="max-w-6xl mx-auto px-6 mt-20 mb-16">

    @if(session('success'))
        <div id="success-message" class="fixed top-10 right-10 z-50 bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl animate-fade-in">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('success-message');
                if(msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    <form action="{{ route('user.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="flex items-center gap-8 mb-16">
            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 shadow-inner flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name={{ $user->UTI_NOM_UTILISATEUR }}&size=128&background=random" alt="Avatar">
            </div>
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ $user->UTI_NOM_UTILISATEUR }}</h1>
            </div>
        </div>

        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes informations</h2>
            <div class="border-t border-gray-100 pt-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                    
                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Nom
                            <button type="button" onclick="enableField('UTI_NOM')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="text" id="UTI_NOM" name="UTI_NOM" value="{{ $user->UTI_NOM }}" readonly 
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>

                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Prénom
                            <button type="button" onclick="enableField('UTI_PRENOM')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="text" id="UTI_PRENOM" name="UTI_PRENOM" value="{{ $user->UTI_PRENOM }}" readonly 
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide">Date de naissance</label>
                        <input type="text" value="{{ $user->UTI_DATE_NAISSANCE->format('d/m/Y') }}" disabled 
                            class="w-full p-4 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-12 pt-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Coordonnées & Sécurité</h2>
            <div class="border-t border-gray-100 pt-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                    
                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Email
                            <button type="button" onclick="enableField('UTI_EMAIL')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="email" id="UTI_EMAIL" name="UTI_EMAIL" value="{{ $user->UTI_EMAIL }}" readonly
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>

                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Rue
                            <button type="button" onclick="enableField('UTI_RUE')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="text" id="UTI_RUE" name="UTI_RUE" value="{{ $user->UTI_RUE }}" readonly
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>

                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Code Postal
                            <button type="button" onclick="enableField('UTI_CODE_POSTAL')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="text" id="UTI_CODE_POSTAL" name="UTI_CODE_POSTAL" value="{{ $user->UTI_CODE_POSTAL }}" readonly
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>

                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Ville
                            <button type="button" onclick="enableField('UTI_VILLE')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="text" id="UTI_VILLE" name="UTI_VILLE" value="{{ $user->UTI_VILLE }}" readonly
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>

                    <div class="flex flex-col gap-2 group">
                        <label class="text-sm font-bold text-gray-500 uppercase tracking-wide flex justify-between">
                            Téléphone
                            <button type="button" onclick="enableField('UTI_TELEPHONE')" class="text-black hover:text-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </label>
                        <input type="text" id="UTI_TELEPHONE" name="UTI_TELEPHONE" value="{{ $user->UTI_TELEPHONE }}" readonly
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-black outline-none transition-all font-medium text-gray-800 pointer-events-none cursor-default">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 flex justify-center">
            <button type="submit" class="bg-black font-bold text-white px-16 py-4 rounded-xl text-lg shadow-xl hover:bg-green-600 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-tighter">
                Enregistrer les modifications
            </button>
        </div>
    </form>

    <div class="mt-24 border-t border-gray-100 pt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 uppercase tracking-tight flex items-center gap-2">
            Historique des Courses
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($uniqueCourses as $course)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all hover:-translate-y-1">
                    {{-- Badge Type --}}
                    <div class="mb-3">
                         <span class="text-[10px] font-bold uppercase text-gray-500">
                            {{ $course->type->TYP_LIBELLE ?? 'Course' }}
                        </span>
                    </div>

                    {{-- Nom & Raid --}}
                    <h3 class="font-black text-gray-900 uppercase text-lg leading-tight truncate" title="{{ $course->COU_NOM }}">
                        {{ $course->COU_NOM }}
                    </h3>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1 mb-4 truncate">
                        {{ $course->raid->RAI_NOM }}
                    </p>

                    {{-- Infos Minimales --}}
                    <div class="flex justify-between items-end border-t border-gray-100 pt-3">
                        <div class="text-xs text-gray-500 font-bold">
                             <span class="block text-[10px] uppercase text-gray-300">Date</span>
                             {{ \Carbon\Carbon::parse($course->COU_DATE_DEBUT)->format('d/m/Y') }}
                             <span class="block text-[10px] uppercase text-gray-300">Lieu</span>
                             {{ Str::limit($course->COU_LIEU, 50) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold text-sm italic">Aucune course terminée.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-16 border-t border-gray-100 pt-12">
        <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight flex items-center gap-2">
            Mes Équipes
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($allTeams as $equipe)
                @php
                    $isChef = ($equipe->UTI_ID == Auth::id());
                @endphp

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex flex-col h-full">
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                            @if($equipe->EQU_IMAGE)
                                <img src="{{ asset('storage/' . $equipe->EQU_IMAGE) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl">🚩</span>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="font-black text-gray-900 uppercase text-md truncate" title="{{ $equipe->EQU_NOM }}">
                                {{ $equipe->EQU_NOM }}
                            </h3>
                            @if($isChef)
                                <span class="text-[10px] font-bold uppercase text-yellow-600 bg-yellow-50 px-1 rounded">👑 Chef d'équipe</span>
                            @else
                                <span class="text-[10px] font-bold uppercase text-blue-600 bg-blue-50 px-1 rounded">👤 Membre</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4 flex-grow">
                        <p class="text-xs text-gray-400 uppercase font-bold">Participation à :</p>
                        <p class="text-sm font-bold text-gray-800 truncate">{{ $equipe->course->COU_NOM }}</p>
                    </div>

                    {{-- Bouton Action --}}
                    <div class="mt-auto">
                        <a href="{{ route('teams.show', [$equipe->RAI_ID, $equipe->COU_ID, $equipe->EQU_ID]) }}" 
                           class="block w-full text-center bg-black text-white py-2 rounded-lg font-bold text-xs hover:bg-green-600 transition-colors uppercase tracking-wide">
                            Voir l'équipe
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold text-sm italic">Aucune équipe rejointe.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function enableField(id) {
        const input = document.getElementById(id);
        input.removeAttribute('readonly');
        input.style.pointerEvents = 'auto';
        input.style.cursor = 'text';
        input.classList.remove('bg-gray-50');
        input.classList.add('bg-white');
        input.focus();
    }
</script>

<style>
    input[readonly] {
        pointer-events: none;
        user-select: none;
    }
</style>
@endsection