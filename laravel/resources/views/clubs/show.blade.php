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

                <div>
                    <h3 class="club-details-section">Responsable</h3>
                    <dl class="club-details-dl">
                        <div>
                            <dt class="club-details-dt">Nom complet</dt>
                            <dd class="club-details-dd">
                                @if($club->responsable)
                                    {{ $club->responsable->UTI_PRENOM }} {{ $club->responsable->UTI_NOM }}
                                    @if(!empty($pending))
                                        <small class="text-orange-600">&nbsp;(en attente de validation)</small>
                                    @endif
                                @else
                                    Non défini
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="club-details-dt">Email</dt>
                            <dd class="club-details-dd">{{ $club->responsable ? $club->responsable->UTI_EMAIL : 'Non défini' }}</dd>
                        </div>
                        <div>
                            <dt class="club-details-dt">Nom d'utilisateur</dt>
                            <dd class="club-details-dd">{{ $club->responsable ? $club->responsable->UTI_NOM_UTILISATEUR : 'Non défini' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="club-details-actions">
                <a href="{{ route('clubs.index') }}" class="club-cancel-link">Retour à la liste</a>
                @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isResponsableOf($club)))
                    <div>
                        <a href="{{ route('clubs.edit', $club) }}" class="club-btn">
                            Modifier
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('clubs.destroy', $club) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="club-delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?')">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@if(isset($club->coureurs) && $club->coureurs->isNotEmpty())
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <h2 class="text-xl font-semibold mb-3">Adhérents</h2>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($club->coureurs as $member)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $member->UTI_NOM }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $member->UTI_PRENOM }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->UTI_EMAIL }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection