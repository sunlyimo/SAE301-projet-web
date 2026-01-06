@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header bg-primary bg-gradient text-white p-4 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-person-running fa-2x me-3"></i>
                        <div>
                            <h3 class="mb-0 fw-bold">Nouveau Raid</h3>
                            <small class="text-white-50">Configuration de l'événement et de la logistique</small>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li><i class="fas fa-triangle-exclamation me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('raids.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Section 1: Infos générales -->
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-primary rounded-pill me-2 p-2">1</span>
                            <h5 class="mb-0 fw-bold text-primary section-title">Informations générales</h5>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="text" name="RAI_NOM" id="RAI_NOM" value="{{ old('RAI_NOM') }}"
                                   class="form-control @error('RAI_NOM') is-invalid @enderror" placeholder="Nom du raid">
                            <label for="RAI_NOM">Nom de l'événement</label>
                            @error('RAI_NOM') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="RAI_LIEU" id="RAI_LIEU" value="{{ old('RAI_LIEU') }}" class="form-control" placeholder="Lieu">
                                    <label for="RAI_LIEU"><i class="fas fa-map-marker-alt me-1"></i> Lieu de départ</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="url" name="RAI_WEB" id="RAI_WEB" value="{{ old('RAI_WEB') }}" class="form-control" placeholder="Site web">
                                    <label for="RAI_WEB"><i class="fas fa-globe me-1"></i> Site internet</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Dates clés -->
                        <div class="d-flex align-items-center mb-4 mt-5">
                            <span class="badge bg-primary rounded-pill me-2 p-2">2</span>
                            <h5 class="mb-0 fw-bold text-primary section-title">Dates clés</h5>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Événement</label>
                                    <div class="mb-2">
                                        <label class="small text-muted">Début</label>
                                        <input type="datetime-local" name="RAI_RAID_DATE_DEBUT" class="form-control">
                                    </div>
                                    <div>
                                        <label class="small text-muted">Fin</label>
                                        <input type="datetime-local" name="RAI_RAID_DATE_FIN" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light text-primary border-primary border-opacity-25">
                                    <label class="form-label fw-bold small text-uppercase">Inscriptions</label>
                                    <div class="mb-2">
                                        <label class="small text-muted">Ouverture</label>
                                        <input type="datetime-local" name="RAI_INSCRI_DATE_DEBUT" class="form-control border-primary border-opacity-25">
                                    </div>
                                    <div>
                                        <label class="small text-muted">Clôture</label>
                                        <input type="datetime-local" name="RAI_INSCRI_DATE_FIN" class="form-control border-primary border-opacity-25">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Responsables -->
                        <div class="d-flex align-items-center mb-4 mt-5">
                            <span class="badge bg-primary rounded-pill me-2 p-2">3</span>
                            <h5 class="mb-0 fw-bold text-primary section-title">Responsables</h5>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="CLU_ID" id="CLU_ID" class="form-select">
                                        <option value="">Choisir un club...</option>
                                        @foreach($clubs ?? [] as $club)
                                            <option value="{{ $club->CLU_ID }}">{{ $club->CLU_NOM }}</option>
                                        @endforeach
                                    </select>
                                    <label for="CLU_ID">Club Organisateur</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="UTI_ID" id="UTI_ID" class="form-select">
                                        <option value="">Choisir un responsable...</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->UTI_ID }}">{{ $user->UTI_PRENOM }} {{ $user->UTI_NOM }}</option>
                                        @endforeach
                                    </select>
                                    <label for="UTI_ID">Responsable</label>
                                </div>
                            </div>
                        </div>

                        <!-- Image de couverture -->
                        <div class="mb-5">
                            <label class="form-label fw-bold text-muted small">IMAGE DE COUVERTURE</label>
                            <div class="input-group">
                                <input type="file" name="RAI_IMAGE" class="form-control py-3 rounded-3 shadow-sm">
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="row g-3">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3 rounded-3">
                                    <i class="fas fa-plus-circle me-2"></i>Enregistrer le Raid
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn btn-outline-secondary btn-lg w-100 py-3 rounded-3">
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
