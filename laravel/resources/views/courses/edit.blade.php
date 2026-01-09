@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <a href="{{ route('courses.my-courses') }}" class="inline-flex items-center text-gray-500 hover:text-black font-bold mb-8 transition-all group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Annuler les modifications
    </a>

    <h1 class="text-4xl font-bold text-gray-900 mb-10 tracking-tighter uppercase text-yellow-500">Modifier la course</h1>

    <form action="{{ route('courses.update', [$course->RAI_ID, $course->COU_ID]) }}" method="POST" class="bg-white p-10 rounded-3xl border border-gray-100 shadow-2xl space-y-10">
        @csrf
        @method('PATCH')

        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-yellow-600 uppercase tracking-widest text-sm">Informations Générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase italic">Nom de la course</label>
                    <input type="text" name="COU_NOM" value="{{ old('COU_NOM', $course->COU_NOM) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 transition-all focus:ring-0 outline-none font-bold @error('COU_NOM') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_NOM') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase italic">Type de course</label>
                    <select name="TYP_ID" required class="w-full p-4 bg-gray-50 rounded-2xl border-2 transition-all focus:ring-0 outline-none font-bold appearance-none @error('TYP_ID') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                        @foreach($types as $type)
                            <option value="{{ $type->TYP_ID }}" {{ (old('TYP_ID', $course->TYP_ID) == $type->TYP_ID) ? 'selected' : '' }}>
                                {{ $type->TYP_DESCRIPTION }}
                            </option>
                        @endforeach
                    </select>
                    @error('TYP_ID') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-400 uppercase italic">Lieu</label>
                <input type="text" name="COU_LIEU" value="{{ old('COU_LIEU', $course->COU_LIEU) }}" 
                    class="w-full p-4 bg-gray-50 rounded-2xl border-2 transition-all focus:ring-0 outline-none font-bold @error('COU_LIEU') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                @error('COU_LIEU') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-yellow-600 uppercase tracking-widest text-sm">Timing</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Début</label>
                    <input type="datetime-local" name="COU_DATE_DEBUT" value="{{ old('COU_DATE_DEBUT', \Carbon\Carbon::parse($course->COU_DATE_DEBUT)->format('Y-m-d\TH:i')) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('COU_DATE_DEBUT') border-red-500 @else border-transparent @enderror">
                    @error('COU_DATE_DEBUT') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Fin</label>
                    <input type="datetime-local" name="COU_DATE_FIN" value="{{ old('COU_DATE_FIN', \Carbon\Carbon::parse($course->COU_DATE_FIN)->format('Y-m-d\TH:i')) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('COU_DATE_FIN') border-red-500 @else border-transparent @enderror">
                    @error('COU_DATE_FIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Section 3 : Âges et Difficulté --}}
        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-yellow-600 uppercase tracking-widest text-sm">Paramètres techniques</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Âge Min</label>
                    <input type="number" name="COU_AGE_MIN" value="{{ old('COU_AGE_MIN', $course->COU_AGE_MIN) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('COU_AGE_MIN') border-red-500 @else border-transparent @enderror">
                    @error('COU_AGE_MIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Âge Seul</label>
                    <input type="number" name="COU_AGE_SEUL" value="{{ old('COU_AGE_SEUL', $course->COU_AGE_SEUL) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('COU_AGE_SEUL') border-red-500 @else border-transparent @enderror">
                    @error('COU_AGE_SEUL') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Âge Accomp.</label>
                    <input type="number" name="COU_AGE_ACCOMPAGNATEUR" value="{{ old('COU_AGE_ACCOMPAGNATEUR', $course->COU_AGE_ACCOMPAGNATEUR) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('COU_AGE_ACCOMPAGNATEUR') border-red-500 @else border-transparent @enderror">
                    @error('COU_AGE_ACCOMPAGNATEUR') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase font-black">Difficulté (1-5)</label>
                    <input type="number" name="DIF_NIVEAU" min="1" max="5" value="{{ old('DIF_NIVEAU', $course->DIF_NIVEAU) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('DIF_NIVEAU') border-red-500 @else border-transparent @enderror font-bold">
                    @error('DIF_NIVEAU') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-yellow-600 uppercase tracking-widest text-sm">Tarifs</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Prix Adulte (€)</label>
                    <input type="number" step="0.01" min="0" name="COU_PRIX" value="{{ old('COU_PRIX', $course->COU_PRIX) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-green-600 focus:ring-0 outline-none @error('COU_PRIX') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_PRIX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Prix Enfant (€)</label>
                    <input type="number" step="0.01" min="0" name="COU_PRIX_ENFANT" value="{{ old('COU_PRIX_ENFANT', $course->COU_PRIX_ENFANT) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-green-600 focus:ring-0 outline-none @error('COU_PRIX_ENFANT') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_PRIX_ENFANT') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Prix Repas (€)</label>
                    <input type="number" step="0.01" min="0" name="COU_REPAS_PRIX" value="{{ old('COU_REPAS_PRIX', $course->COU_REPAS_PRIX) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-blue-600 focus:ring-0 outline-none @error('COU_REPAS_PRIX') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_REPAS_PRIX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-400 uppercase">Réduction (€)</label>
                <input type="number" step="0.01" min="0" name="COU_REDUCTION" value="{{ old('COU_REDUCTION', $course->COU_REDUCTION) }}" 
                    class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold text-red-500 focus:ring-0 outline-none @error('COU_REDUCTION') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                @error('COU_REDUCTION') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        <section class="space-y-6 bg-yellow-50 p-6 rounded-2xl border border-yellow-100">
            <h2 class="text-xl font-bold border-b border-yellow-200 pb-2 text-yellow-700 uppercase tracking-widest text-sm flex items-center">
                Responsable de la course
            </h2>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase">Responsable actuel</label>
                @if((isset($isAdmin) && $isAdmin) || (isset($isRaidResponsable) && $isRaidResponsable))
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none z-10">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" 
                               list="users_list" 
                               id="responsable_search" 
                               placeholder="Commencez à taper un pseudo..." 
                               autocomplete="off"
                               class="w-full p-4 pl-12 bg-white rounded-2xl border-2 focus:ring-0 outline-none transition-all font-bold text-gray-800 placeholder-gray-300 @error('responsable_id') border-red-500 @else border-yellow-200 focus:border-yellow-500 @enderror"
                               value="{{ $course->responsable ? ($course->responsable->UTI_NOM_UTILISATEUR . ' (' . $course->responsable->UTI_PRENOM . ' ' . $course->responsable->UTI_NOM . ')') : '' }}">
                    </div>
                    <datalist id="users_list">
                        @foreach($users as $user)
                            <option data-id="{{ $user->UTI_ID }}" value="{{ $user->UTI_NOM_UTILISATEUR }} ({{ $user->UTI_PRENOM }} {{ $user->UTI_NOM }})">
                        @endforeach
                    </datalist>

                    <input type="hidden" name="responsable_id" id="responsable_id" value="{{ old('responsable_id', $course->UTI_ID) }}">
                @else
                    <div class="w-full p-4 bg-gray-100 rounded-2xl border-2 border-gray-200 font-bold text-gray-700">
                        {{ $course->responsable ? $course->responsable->UTI_PRENOM . ' ' . $course->responsable->UTI_NOM : 'Non défini' }}
                    </div>
                    <input type="hidden" name="responsable_id" value="{{ $course->UTI_ID }}">
                @endif
            </div>
        </section>

        <section class="space-y-6">
            <h2 class="text-xl font-bold border-b pb-2 text-yellow-600 uppercase tracking-widest text-sm">Capacité & Équipes</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Participants Min</label>
                    <input type="number" name="COU_PARTICIPANT_MIN" value="{{ old('COU_PARTICIPANT_MIN', $course->COU_PARTICIPANT_MIN) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold focus:ring-0 outline-none @error('COU_PARTICIPANT_MIN') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_PARTICIPANT_MIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Participants Max</label>
                    <input type="number" name="COU_PARTICIPANT_MAX" value="{{ old('COU_PARTICIPANT_MAX', $course->COU_PARTICIPANT_MAX) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold focus:ring-0 outline-none @error('COU_PARTICIPANT_MAX') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_PARTICIPANT_MAX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Équipes Min</label>
                    <input type="number" name="COU_EQUIPE_MIN" value="{{ old('COU_EQUIPE_MIN', $course->COU_EQUIPE_MIN) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold focus:ring-0 outline-none @error('COU_EQUIPE_MIN') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_EQUIPE_MIN') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Équipes Max</label>
                    <input type="number" name="COU_EQUIPE_MAX" value="{{ old('COU_EQUIPE_MAX', $course->COU_EQUIPE_MAX) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 font-bold focus:ring-0 outline-none @error('COU_EQUIPE_MAX') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_EQUIPE_MAX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Max / Équipe</label>
                    <input type="number" name="COU_PARTICIPANT_PAR_EQUIPE_MAX" value="{{ old('COU_PARTICIPANT_PAR_EQUIPE_MAX', $course->COU_PARTICIPANT_PAR_EQUIPE_MAX) }}" 
                        class="w-full p-4 bg-gray-50 rounded-2xl border-2 @error('COU_PARTICIPANT_PAR_EQUIPE_MAX') border-red-500 @else border-transparent focus:border-yellow-500 @enderror">
                    @error('COU_PARTICIPANT_PAR_EQUIPE_MAX') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <button type="submit" class="w-full py-5 bg-yellow-500 text-black font-black rounded-2xl hover:bg-black hover:text-white transition-all shadow-xl transform hover:-translate-y-1 uppercase tracking-widest">
            Enregistrer les modifications
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    var searchInput = document.getElementById('responsable_search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            var input = e.target;
            var list = document.getElementById('users_list');
            var hiddenInput = document.getElementById('responsable_id');
            for (var i = 0; i < list.options.length; i++) {
                if (list.options[i].value === input.value) {
                    hiddenInput.value = list.options[i].getAttribute('data-id');
                    input.classList.remove('border-yellow-200', 'focus:border-yellow-500');
                    input.classList.add('border-green-500', 'focus:border-green-600');
                    return;
                }
            }
            hiddenInput.value = "";
            input.classList.remove('border-green-500', 'focus:border-green-600');
            input.classList.add('border-yellow-200', 'focus:border-yellow-500');
        });
    }
</script>
@endsection