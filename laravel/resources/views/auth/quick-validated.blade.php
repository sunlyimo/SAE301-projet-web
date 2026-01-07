@extends('layouts.app')

@section('title', 'Inscription validée automatiquement')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<style>
.quick-validated-container {
    max-width: 600px;
    margin: 0 auto;
}

.validation-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.validation-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.account-details {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.account-details h4 {
    margin-top: 0;
    color: #495057;
}

.finalize-btn {
    background-color: #007bff;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    transition: background-color 0.2s;
}

.finalize-btn:hover {
    background-color: #0056b3;
    color: white;
    text-decoration: none;
}
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card quick-validated-container">
        <div class="auth-header">
            <h1 class="auth-title">✅ Inscription validée automatiquement</h1>
            <p class="auth-subtitle">Club "{{ $club->CLU_NOM }}"</p>
        </div>

        <div class="validation-success">
            <h3 style="margin-top: 0;">🎉 Félicitations !</h3>
            <p>Votre inscription en tant que responsable du club <strong>"{{ $club->CLU_NOM }}"</strong> a été validée automatiquement.</p>
            <p>Un compte utilisateur temporaire a été créé avec des informations par défaut.</p>
        </div>

        <div class="account-details">
            <h4>📋 Informations de votre compte temporaire</h4>
            <div style="margin-bottom: 15px;">
                <p><strong>Nom d'utilisateur :</strong> {{ $club->responsable->UTI_NOM_UTILISATEUR }}</p>
                <p><strong>Email :</strong> {{ $club->responsable->UTI_EMAIL }}</p>
                <p><strong>Mot de passe temporaire :</strong> <code>TempPass123!</code></p>
            </div>
            <p style="color: #6c757d; font-size: 14px;">
                ⚠️ <strong>Important :</strong> Ce mot de passe est temporaire. Vous pourrez le changer lors de la finalisation de votre inscription.
            </p>
        </div>

        <div class="validation-warning">
            <h4 style="margin-top: 0;">📝 Action requise</h4>
            <p>Pour finaliser votre inscription et personnaliser vos informations, cliquez sur le bouton ci-dessous :</p>
        </div>

        <div class="form-actions">
            <a href="{{ route('responsable.register', ['club_id' => $club->CLU_ID, 'token' => $token]) }}" class="finalize-btn">
                ✏️ Finaliser mon inscription
            </a>
        </div>

        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <p style="color: #6c757d; font-size: 14px; text-align: center;">
                Vous pouvez aussi vous connecter directement avec le mot de passe temporaire et modifier vos informations plus tard.
            </p>
        </div>
    </div>
</div>
@endsection