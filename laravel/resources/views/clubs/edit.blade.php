@extends('layouts.app')

@section('title', 'Modifier le club')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/clubs.css') }}">
@endpush

@section('content')
<div class="club-container club-content">
    <div class="mb-6">
        <h1 class="club-title">Modifier le club</h1>
    </div>

    <div class="club-card">
        <div class="club-card-content">
            <form method="POST" action="{{ route('clubs.update', $club) }}">
                @csrf
                @method('PUT')

                <div class="club-form-group">
                    <label for="CLU_NOM" class="club-label">Nom du club</label>
                    <input type="text" name="CLU_NOM" id="CLU_NOM" value="{{ old('CLU_NOM', $club->CLU_NOM) }}" class="club-input" required>
                    @error('CLU_NOM')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="CLU_RUE" class="club-label">Rue</label>
                    <input type="text" name="CLU_RUE" id="CLU_RUE" value="{{ old('CLU_RUE', $club->CLU_RUE) }}" class="club-input" required>
                    @error('CLU_RUE')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="CLU_CODE_POSTAL" class="club-label">Code Postal</label>
                    <input type="text" name="CLU_CODE_POSTAL" id="CLU_CODE_POSTAL" value="{{ old('CLU_CODE_POSTAL', $club->CLU_CODE_POSTAL) }}" class="club-input" required>
                    @error('CLU_CODE_POSTAL')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="CLU_VILLE" class="club-label">Ville</label>
                    <input type="text" name="CLU_VILLE" id="CLU_VILLE" value="{{ old('CLU_VILLE', $club->CLU_VILLE) }}" class="club-input" required>
                    @error('CLU_VILLE')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label class="club-label">Responsable du club</label>

                    <div style="margin-bottom:8px;">
                        <label for="selected_responsable_id">Choisir un utilisateur existant</label>
                        <select name="selected_responsable_id" id="selected_responsable_id" class="club-input" required>
                            <option value="">-- Choisir un responsable --</option>
                            @foreach($availableUsers ?? [] as $user)
                                <option value="{{ $user->UTI_ID }}" {{ ($club->responsable && $user->UTI_ID == $club->responsable->UTI_ID) ? 'selected' : '' }}>
                                    {{ $user->UTI_NOM }} {{ $user->UTI_PRENOM }} ({{ $user->UTI_EMAIL }})
                                </option>
                            @endforeach
                        </select>
                        @error('selected_responsable_id')
                            <p class="club-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="club-responsable-row">
                        <div class="club-responsable-field">
                            <input type="text" name="RESP_NOM" id="RESP_NOM" value="{{ old('RESP_NOM', $club->responsable->UTI_NOM ?? '') }}" class="club-input" placeholder="Nom" required>
                            @error('RESP_NOM')
                                <p class="club-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="club-responsable-field">
                            <input type="text" name="RESP_PRENOM" id="RESP_PRENOM" value="{{ old('RESP_PRENOM', $club->responsable->UTI_PRENOM ?? '') }}" class="club-input" placeholder="Prénom" required>
                            @error('RESP_PRENOM')
                                <p class="club-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="club-responsable-email">
                        <input type="email" name="RESP_EMAIL" id="RESP_EMAIL" value="{{ old('RESP_EMAIL', $club->responsable->UTI_EMAIL ?? '') }}" class="club-input" placeholder="Email du responsable" required>
                        @error('RESP_EMAIL')
                            <p class="club-error">{{ $message }}</p>
                            @enderror
                    </div>
                    <div class="club-responsable-username">
                        <input type="text" name="RESP_NOM_UTILISATEUR" id="RESP_NOM_UTILISATEUR" value="{{ old('RESP_NOM_UTILISATEUR', $club->responsable->UTI_NOM_UTILISATEUR ?? '') }}" class="club-input" placeholder="Nom d'utilisateur du responsable" required>
                        @error('RESP_NOM_UTILISATEUR')
                            <p class="club-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <script>
                        (function(){
                            const select = document.getElementById('selected_responsable_id');
                            const inputs = ['RESP_NOM','RESP_PRENOM','RESP_EMAIL','RESP_NOM_UTILISATEUR'].map(id => document.getElementById(id));
                            const userData = @json($userData);

                            function toggle() {
                                if (select && select.value) {
                                    // existing user selected: populate and disable new fields
                                    const id = select.value;
                                    const user = userData.find(u => u.id == id);
                                    if (user) {
                                        document.getElementById('RESP_NOM').value = user.nom;
                                        document.getElementById('RESP_PRENOM').value = user.prenom;
                                        document.getElementById('RESP_EMAIL').value = user.email;
                                        document.getElementById('RESP_NOM_UTILISATEUR').value = user.nom_utilisateur;
                                    }
                                    inputs.forEach(i => {
                                        i.disabled = true;
                                        i.required = false;
                                    });
                                } else {
                                    // no user selected: enable fields for manual input
                                    inputs.forEach(i => {
                                        i.disabled = false;
                                        i.required = true;
                                    });
                                }
                            }

                            if (select) {
                                select.addEventListener('change', toggle);
                                // initial call
                                toggle();
                            }
                        })();
                    </script>
                </div>

                <div class="club-form-actions">
                    <a href="{{ route('clubs.show', $club) }}" class="club-cancel-link">Annuler</a>
                    <button type="submit" class="club-btn">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection