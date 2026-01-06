@extends('layouts.app')

@section('title', 'Détails du club')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/clubs.css') }}">
@endpush

@section('content')
<div class="club-container club-content">
    <div class="mb-6">
        <h1 class="club-title">{{ $club->CLU_NOM }}</h1>
    </div>

    <div class="club-card">
        <div class="club-card-content">
            <div class="club-details-grid">
                <div>
                    <h3 class="club-details-section">Informations</h3>
                    <dl class="club-details-dl">
                        <div>
                            <dt class="club-details-dt">Nom</dt>
                            <dd class="club-details-dd">{{ $club->CLU_NOM }}</dd>
                        </div>
                        <div>
                            <dt class="club-details-dt">Rue</dt>
                            <dd class="club-details-dd">{{ $club->CLU_RUE }}</dd>
                        </div>
                        <div>
                            <dt class="club-details-dt">Code Postal</dt>
                            <dd class="club-details-dd">{{ $club->CLU_CODE_POSTAL }}</dd>
                        </div>
                        <div>
                            <dt class="club-details-dt">Ville</dt>
                            <dd class="club-details-dd">{{ $club->CLU_VILLE }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="club-details-actions">
                <a href="{{ route('clubs.index') }}" class="club-cancel-link">Retour à la liste</a>
                <div>
                    <a href="{{ route('clubs.edit', $club) }}" class="club-btn">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('clubs.destroy', $club) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="club-delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?')">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection