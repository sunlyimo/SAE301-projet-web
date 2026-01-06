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
                    <label for="name" class="club-label">Nom du club</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $club->name) }}" class="club-input" required>
                    @error('name')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="address" class="club-label">Adresse</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $club->address) }}" class="club-input" required>
                    @error('address')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="manager_name" class="club-label">Nom du responsable</label>
                    <input type="text" name="manager_name" id="manager_name" value="{{ old('manager_name', $club->manager_name) }}" class="club-input" required>
                    @error('manager_name')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
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