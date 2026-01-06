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
                <p class="text-gray-500 font-medium">Membre depuis {{ $user->created_at->format('M Y') }}</p>
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

    <div class="mt-24 border-t border-gray-100 pt-16">
        <h2 class="text-3xl text-center font-bold text-gray-900 mb-10 uppercase">
            Historique des courses
        </h2>
        <div class="text-center py-10 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-sm italic">Aucune donnée disponible</p>
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