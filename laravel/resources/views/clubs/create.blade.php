@extends('layouts.app')

@section('title', 'Ajouter un club')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/clubs.css') }}">
@endpush

@section('content')
<div class="club-container club-content">
    <div class="mb-6">
        <h1 class="club-title">Ajouter un club</h1>
    </div>

    <div class="club-card">
        <div class="club-card-content">
            <form method="POST" action="{{ route('clubs.store') }}">
                @csrf

                <div class="club-form-group">
                    <label for="name" class="club-label">Nom du club</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="club-input" required>
                    @error('name')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="address" class="club-label">Adresse</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="club-input" required>
                    @error('address')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-group">
                    <label for="manager_name" class="club-label">Nom du responsable</label>
                    <input type="text" name="manager_name" id="manager_name" value="{{ old('manager_name') }}" class="club-input" required>
                    @error('manager_name')
                        <p class="club-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="club-form-actions">
                    <a href="{{ route('clubs.index') }}" class="club-cancel-link">Annuler</a>
                    <button type="submit" class="club-btn">
                        Créer le club
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection