@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <a href="{{ route('courses.index') }}" class="inline-flex items-center text-gray-500 hover:text-black font-bold mb-8 transition-all group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Retour à la liste
    </a>

    <h1 class="text-4xl font-bold text-gray-900 mb-10 tracking-tighter uppercase">Créer une nouvelle course</h1>

    <form action="{{ route('courses.store') }}" method="POST" class="bg-white p-10 rounded-3xl shadow-2xl border border-gray-100 space-y-10">
        @csrf

        {{-- Informations Générales --}}
        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-green-600 uppercase tracking-widest text-sm">Informations Générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Nom de la course</label>
                    <input type="text" name="COU_NOM" value="{{ old('COU_NOM') }}" placeholder="Ex: Grand Trail du Nord" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 outline-none transition-all @error('COU_NOM') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_NOM') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase italic">Type de course</label>
                    <select name="TYP_ID" required 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 outline-none transition-all font-bold appearance-none @error('TYP_ID') border-red-500 @else border-transparent focus:border-black @enderror">
                        <option value="" disabled {{ old('TYP_ID') ? '' : 'selected' }}>Choisir un type...</option>
                        @foreach($types as $type)
                            <option value="{{ $type->TYP_ID }}" {{ old('TYP_ID') == $type->TYP_ID ? 'selected' : '' }}>
                                {{ $type->TYP_DESCRIPTION }}
                            </option>
                        @endforeach
                    </select>
                    @error('TYP_ID') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-400 uppercase">Lieu</label>
                <input type="text" name="COU_LIEU" value="{{ old('COU_LIEU') }}" placeholder="Ex: Parc des sports, Ifs" 
                    class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 outline-none transition-all @error('COU_LIEU') border-red-500 @else border-transparent focus:border-black @enderror">
                @error('COU_LIEU') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Horaires --}}
        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-green-600 uppercase tracking-widest text-sm">Horaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Début</label>
                    <input type="datetime-local" name="COU_DATE_DEBUT" value="{{ old('COU_DATE_DEBUT') }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_DATE_DEBUT') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_DATE_DEBUT') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Fin</label>
                    <input type="datetime-local" name="COU_DATE_FIN" value="{{ old('COU_DATE_FIN') }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_DATE_FIN') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_DATE_FIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Âges et Difficulté --}}
        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-green-600 uppercase tracking-widest text-sm">Âges et Difficulté</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Âge Min</label>
                    <input type="number" name="COU_AGE_MIN" value="{{ old('COU_AGE_MIN') }}" min="0"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_AGE_MIN') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_AGE_MIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Âge Seul</label>
                    <input type="number" name="COU_AGE_SEUL" value="{{ old('COU_AGE_SEUL') }}" min="0"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_AGE_SEUL') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_AGE_SEUL') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Âge Accomp.</label>
                    <input type="number" name="COU_AGE_ACCOMPAGNATEUR" value="{{ old('COU_AGE_ACCOMPAGNATEUR') }}" min="0"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_AGE_ACCOMPAGNATEUR') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_AGE_ACCOMPAGNATEUR') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Difficulté (1-5)</label>
                    <input type="number" name="DIF_NIVEAU" value="{{ old('DIF_NIVEAU') }}" min="1" max="5"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('DIF_NIVEAU') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('DIF_NIVEAU') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Tarification --}}
        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-green-600 uppercase tracking-widest text-sm">Tarifs</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Prix Adulte (€)</label>
                    <input type="number" step="0.01" min="0" name="COU_PRIX" value="{{ old('COU_PRIX') }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-green-600 focus:ring-0 transition-all @error('COU_PRIX') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_PRIX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Prix Enfant (€)</label>
                    <input type="number" step="0.01" min="0" name="COU_PRIX_ENFANT" value="{{ old('COU_PRIX_ENFANT') }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-green-600 focus:ring-0 transition-all @error('COU_PRIX_ENFANT') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_PRIX_ENFANT') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Prix Repas (€)</label>
                    <input type="number" step="0.01" min="0" name="COU_REPAS_PRIX" value="{{ old('COU_REPAS_PRIX') }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-blue-600 focus:ring-0 transition-all @error('COU_REPAS_PRIX') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_REPAS_PRIX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-400 uppercase">Réduction (€)</label>
                <input type="number" step="0.01" min="0" name="COU_REDUCTION" value="{{ old('COU_REDUCTION', 0) }}" 
                    class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-red-500 focus:ring-0 transition-all @error('COU_REDUCTION') border-red-500 @else border-transparent focus:border-black @enderror">
                @error('COU_REDUCTION') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Capacité & Équipes --}}
        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-green-600 uppercase tracking-widest text-sm">Capacité & Équipes</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Participants Min</label>
                    <input type="number" name="COU_PARTICIPANT_MIN" value="{{ old('COU_PARTICIPANT_MIN') }}" min="1"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_PARTICIPANT_MIN') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_PARTICIPANT_MIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Participants Max</label>
                    <input type="number" name="COU_PARTICIPANT_MAX" value="{{ old('COU_PARTICIPANT_MAX') }}" min="1"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_PARTICIPANT_MAX') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_PARTICIPANT_MAX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Équipes Min</label>
                    <input type="number" name="COU_EQUIPE_MIN" value="{{ old('COU_EQUIPE_MIN', 1) }}" min="1"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_EQUIPE_MIN') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_EQUIPE_MIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Équipes Max</label>
                    <input type="number" name="COU_EQUIPE_MAX" value="{{ old('COU_EQUIPE_MAX') }}" min="1"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_EQUIPE_MAX') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_EQUIPE_MAX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Max / Équipe</label>
                    <input type="number" name="COU_PARTICIPANT_PAR_EQUIPE_MAX" value="{{ old('COU_PARTICIPANT_PAR_EQUIPE_MAX') }}" min="1"
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 focus:ring-0 transition-all @error('COU_PARTICIPANT_PAR_EQUIPE_MAX') border-red-500 @else border-transparent focus:border-black @enderror">
                    @error('COU_PARTICIPANT_PAR_EQUIPE_MAX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <button type="submit" class="w-full py-5 bg-black text-white font-bold rounded-2xl hover:bg-green-600 transition-all shadow-xl transform hover:-translate-y-1 uppercase tracking-widest">
            Publier la course
        </button>
    </form>
</div>
@endsection