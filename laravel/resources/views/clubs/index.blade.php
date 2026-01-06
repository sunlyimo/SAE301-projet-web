@extends('layouts.app')

@section('title', 'Clubs')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/clubs.css') }}">
@endpush

@section('content')
<div class="club-container club-content">
    <div class="club-header">
        <h1 class="club-title">Clubs</h1>
        <a href="{{ route('clubs.create') }}" class="club-btn">
            Ajouter un club
        </a>
    </div>

    @if(session('success'))
        <div class="club-success-alert" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="club-card">
        <div class="club-card-content">
            <table class="club-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Rue</th>
                        <th>Code Postal</th>
                        <th>Ville</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clubs as $club)
                        <tr>
                            <td>{{ $club->CLU_NOM }}</td>
                            <td>{{ $club->CLU_RUE }}</td>
                            <td>{{ $club->CLU_CODE_POSTAL }}</td>
                            <td>{{ $club->CLU_VILLE }}</td>
                            <td class="club-actions">
                                <a href="{{ route('clubs.show', $club) }}" class="club-link">Voir</a>
                                <a href="{{ route('clubs.edit', $club) }}" class="club-link">Modifier</a>
                                <form method="POST" action="{{ route('clubs.destroy', $club) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="club-delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500">Aucun club trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection