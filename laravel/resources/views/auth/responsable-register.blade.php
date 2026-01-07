@extends('layouts.app')

@section('title', 'Finaliser mon inscription - Responsable de club')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<style>
.required-asterisk {
    color: #dc3545;
    font-weight: bold;
    margin-left: 2px;
}

.password-container {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #666;
    font-size: 18px;
    user-select: none;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
}

.password-toggle:hover {
    color: #333;
}

.eye-icon {
    width: 18px;
    height: 18px;
    fill: currentColor;
}
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Finaliser mon inscription</h1>
            <p class="auth-subtitle">Responsable du club "{{ $club->CLU_NOM }}"</p>
        </div>

        <div class="club-info-box">
            <h3>Informations du club</h3>
            <div class="club-info-details">
                <p><strong>Nom du club :</strong> {{ $club->CLU_NOM }}</p>
                <p><strong>Adresse :</strong> {{ $club->CLU_RUE }}, {{ $club->CLU_CODE_POSTAL }} {{ $club->CLU_VILLE }}</p>
                <p><strong>Votre nom d'utilisateur :</strong> {{ $club->responsable->UTI_NOM_UTILISATEUR }}</p>
                <p><strong>Votre email :</strong> {{ $club->responsable->UTI_EMAIL }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('responsable.complete-registration') }}">
            @csrf
            <input type="hidden" name="club_id" value="{{ $club->CLU_ID }}">
            <input type="hidden" name="responsable_id" value="{{ $club->responsable->UTI_ID }}">

            <div class="form-section">
                <h3>Informations pré-remplies</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="UTI_NOM" class="form-label">Nom</label>
                        <input type="text" name="UTI_NOM" id="UTI_NOM" value="{{ old('UTI_NOM', $club->responsable->UTI_NOM) }}" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label for="UTI_PRENOM" class="form-label">Prénom</label>
                        <input type="text" name="UTI_PRENOM" id="UTI_PRENOM" value="{{ old('UTI_PRENOM', $club->responsable->UTI_PRENOM) }}" class="form-input" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="UTI_EMAIL" class="form-label">Email</label>
                    <input type="email" name="UTI_EMAIL" id="UTI_EMAIL" value="{{ old('UTI_EMAIL', $club->responsable->UTI_EMAIL) }}" class="form-input" readonly>
                </div>

                <div class="form-group">
                    <label for="UTI_NOM_UTILISATEUR" class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="UTI_NOM_UTILISATEUR" id="UTI_NOM_UTILISATEUR" value="{{ old('UTI_NOM_UTILISATEUR', $club->responsable->UTI_NOM_UTILISATEUR) }}" class="form-input" readonly>
                </div>
            </div>

            <div class="form-section">
                <h3>Informations à compléter</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="UTI_DATE_NAISSANCE" class="form-label">Date de naissance <span class="required-asterisk">*</span></label>
                        <input type="date" name="UTI_DATE_NAISSANCE" id="UTI_DATE_NAISSANCE" value="{{ old('UTI_DATE_NAISSANCE') }}" class="form-input" required>
                        @error('UTI_DATE_NAISSANCE')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="UTI_LICENCE" class="form-label">Numéro de licence <span class="required-asterisk">*</span></label>
                        <input type="text" name="UTI_LICENCE" id="UTI_LICENCE" value="{{ old('UTI_LICENCE') }}" class="form-input" required>
                        @error('UTI_LICENCE')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="UTI_RUE" class="form-label">Rue <span class="required-asterisk">*</span></label>
                        <input type="text" name="UTI_RUE" id="UTI_RUE" value="{{ old('UTI_RUE') }}" class="form-input" required>
                        @error('UTI_RUE')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="UTI_CODE_POSTAL" class="form-label">Code postal <span class="required-asterisk">*</span></label>
                        <input type="text" name="UTI_CODE_POSTAL" id="UTI_CODE_POSTAL" value="{{ old('UTI_CODE_POSTAL') }}" class="form-input" required>
                        @error('UTI_CODE_POSTAL')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="UTI_VILLE" class="form-label">Ville <span class="required-asterisk">*</span></label>
                        <input type="text" name="UTI_VILLE" id="UTI_VILLE" value="{{ old('UTI_VILLE') }}" class="form-input" required>
                        @error('UTI_VILLE')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="UTI_TELEPHONE" class="form-label">Téléphone <span class="required-asterisk">*</span></label>
                    <input type="tel" name="UTI_TELEPHONE" id="UTI_TELEPHONE" value="{{ old('UTI_TELEPHONE') }}" class="form-input" required>
                    @error('UTI_TELEPHONE')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe <span class="required-asterisk">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" class="form-input" required>
                            <span class="password-toggle" onclick="togglePassword('password')" id="toggle-password" title="Afficher/Masquer le mot de passe">
                                <svg class="eye-icon" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="required-asterisk">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                            <span class="password-toggle" onclick="togglePassword('password_confirmation')" id="toggle-password-confirmation" title="Afficher/Masquer le mot de passe">
                                <svg class="eye-icon" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <p class="text-sm text-gray-600 mb-4">
                    <span class="required-asterisk">*</span> Champs obligatoires
                </p>
                <button type="submit" class="auth-btn">
                    Finaliser mon inscription
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById('toggle-' + inputId);

    if (input.type === 'password') {
        input.type = 'text';
        toggle.title = 'Masquer le mot de passe';
    } else {
        input.type = 'password';
        toggle.title = 'Afficher le mot de passe';
    }
}
</script>
@endsection