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