@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <a href="{{ route('raids.my-raids') }}" class="inline-flex items-center text-gray-500 hover:text-black font-bold mb-8 transition-all group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Retour à Mes Raids
    </a>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-4xl font-bold text-gray-900 mb-10 tracking-tighter uppercase">Créer une nouvelle course</h1>

    @if($raids->isEmpty())
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-2xl text-gray-400 font-bold mb-4">Vous n'êtes responsable d'aucun raid.</p>
            <p class="text-gray-500">Vous ne pouvez pas créer de courses pour le moment.</p>
            <a href="{{ route('courses.index') }}" class="text-black underline font-bold hover:text-green-600 mt-4 inline-block">Retour à la liste des courses</a>
        </div>
    @else

    <form action="{{ route('courses.store') }}" method="POST" class="p-10 rounded-3xl border border-gray-100 space-y-10">
        @csrf
        <section class="space-y-6 bg-blue-50 p-6 rounded-2xl border border-blue-100">
            <h2 class="text-xl font-bold border-b border-blue-200 pb-2 text-blue-700 uppercase tracking-widest text-sm flex items-center">
                Sélection du Raid
            </h2>
            
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase">Raid</label>
                <select name="rai_id" id="rai_id" class="w-full p-4 bg-white rounded-2xl border-2 focus:ring-0 outline-none transition-all font-bold text-gray-800 @error('rai_id') border-red-500 @else border-blue-200 focus:border-blue-500 @enderror" required>
                    <option value="">Sélectionnez un raid</option>
                    @foreach($raids as $raid)
                        <option value="{{ $raid->RAI_ID }}" data-start="{{ $raid->RAI_RAID_DATE_DEBUT }}" data-end="{{ $raid->RAI_RAID_DATE_FIN }}" {{ isset($selectedRaidId) && $selectedRaidId == $raid->RAI_ID ? 'selected' : '' }}>
                            {{ $raid->RAI_NOM }} ({{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN)->format('d/m/Y H:i') }})
                        </option>
                    @endforeach
                </select>
                @error('rai_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        <section class="space-y-6 bg-yellow-50 p-6 rounded-2xl border border-yellow-100">
            <h2 class="text-xl font-bold border-b border-yellow-200 pb-2 text-yellow-700 uppercase tracking-widest text-sm flex items-center">
                Responsable de la course
            </h2>
            
            <div class="space-y-2 relative">
                <label class="text-xs font-bold text-gray-400 uppercase">Rechercher par Nom d'utilisateur</label>
                
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none z-10">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" 
                           list="users_list" 
                           id="responsable_search" 
                           placeholder="Commencez à taper un pseudo..." 
                           autocomplete="off"
                           class="w-full p-4 pl-12 bg-white rounded-2xl border-2 focus:ring-0 outline-none transition-all font-bold text-gray-800 placeholder-gray-300 @error('responsable_id') border-red-500 @else border-yellow-200 focus:border-yellow-500 @enderror">
                </div>
                <datalist id="users_list">
                    @foreach($users as $user)
                        <option data-id="{{ $user->UTI_ID }}" value="{{ $user->UTI_NOM_UTILISATEUR }} ({{ $user->UTI_PRENOM }} {{ $user->UTI_NOM }})">
                    @endforeach
                </datalist>

                <input type="hidden" name="responsable_id" id="responsable_id" value="{{ old('responsable_id') }}">

                @error('responsable_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

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

    <script>
        document.getElementById('rai_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const startDate = selectedOption.getAttribute('data-start');
            const endDate = selectedOption.getAttribute('data-end');
            
            const debutInput = document.querySelector('input[name="COU_DATE_DEBUT"]');
            const finInput = document.querySelector('input[name="COU_DATE_FIN"]');
            
            if (startDate && endDate) {
                // Convert to datetime-local format (YYYY-MM-DDTHH:MM)
                const start = new Date(startDate).toISOString().slice(0, 16);
                const end = new Date(endDate).toISOString().slice(0, 16);
                
                debutInput.min = start;
                debutInput.max = end;
                finInput.min = start;
                finInput.max = end;
            } else {
                debutInput.removeAttribute('min');
                debutInput.removeAttribute('max');
                finInput.removeAttribute('min');
                finInput.removeAttribute('max');
            }
        });
    </script>
</div>

@endif

<script>
    document.getElementById('responsable_search').addEventListener('input', function(e) {
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
</script>
@endsection