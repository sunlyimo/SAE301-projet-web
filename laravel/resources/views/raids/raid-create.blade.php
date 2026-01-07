@extends('layouts.app')
<?php
use Illuminate\Support\Facades\DB;

if (auth()->check() && DB::table('VIK_RESPONSABLE_CLUB')->where('UTI_ID', auth()->id())->exists()) {
    
}
else {
    //abort(403, 'Accès refusé');
}
?>
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="card-header bg-primary bg-gradient text-white p-4 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-person-running fa-2x me-3"></i>
                        <div>
                            <h3 class="mb-0 fw-bold">Nouveau Raid</h3>
                            <small class="text-white-50">Configuration de l'événement et de la logistique</small>
                        </div>
                    </div>
                </div>

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

                        <div class="g-3 mb-4">
                            <div class="form-floating">
                                <input type="text" name="RAI_LIEU" id="RAI_LIEU" value="{{ old('RAI_LIEU') }}" class="form-control" placeholder="Lieu">
                                <label for="RAI_LIEU"><i class="fas fa-map-marker-alt me-1"></i> Lieu de départ</label>
                            </div>
                        </div>

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

                        <div class="d-flex align-items-center mb-4 mt-5">
                            <span class="badge bg-primary rounded-pill me-2 p-2">3</span>
                            <h5 class="mb-0 fw-bold text-primary section-title">Responsables</h5>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="CLU_ID" id="CLU_ID" class="form-select">
                                        <option value="">Choisir un club...</option>
                                        @foreach($clubs ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ old('CLU_ID') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="CLU_ID">Club Organisateur</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input list="responsable_list" id="responsable_input" class="form-control" placeholder="Rechercher un membre..." autocomplete="off" value="{{ old('responsable_name') }}">
                                    <datalist id="responsable_list">
                                        @foreach($responsables ?? [] as $resp)
                                            <option value="{{ $resp->name }}"></option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" name="UTI_ID" id="UTI_ID" value="{{ old('UTI_ID') }}">
                                    <label for="responsable_input">Responsable</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4 mt-5">
                            <span class="badge bg-primary rounded-pill me-2 p-2">4</span>
                            <h5 class="mb-0 fw-bold text-primary section-title">Contact</h5>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" name="RAI_CONTACT" id="RAI_CONTACT" value="{{ old('RAI_CONTACT') }}"
                                        class="form-control @error('RAI_CONTACT') is-invalid @enderror" readonly placeholder="Email">
                                    <label for="RAI_CONTACT"><i class="fas fa-envelope me-1"></i> Email</label>
                                    @error('RAI_CONTACT') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="url" name="RAI_WEB" id="RAI_WEB" value="{{ old('RAI_WEB') }}" class="form-control" placeholder="Site web">
                                    <label for="RAI_WEB"><i class="fas fa-globe me-1"></i> Site internet (facultatif)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-muted small">IMAGE DE COUVERTURE</label>
                            <div class="input-group">
                                <input type="file" name="RAI_IMAGE" class="form-control py-3 rounded-3 shadow-sm">
                            </div>
                        </div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('responsable_input');
    const hidden = document.getElementById('UTI_ID');
    const contactEmail = document.getElementById('RAI_CONTACT');
    const contactPhone = document.getElementById('RAI_TELEPHONE');

    if (!input || !hidden) return;

    const mapping = {};
    @foreach($responsables ?? [] as $resp)
        mapping["{!! addslashes($resp->name) !!}"] = { id: "{{ $resp->UTI_ID }}", email: "{{ $resp->UTI_EMAIL ?? '' }}", phone: "{{ $resp->UTI_TELEPHONE ?? '' }}" };
    @endforeach

    function applyContactForName(name) {
        const entry = mapping[name];
        if (entry) {
            hidden.value = entry.id;
            if (contactEmail) contactEmail.value = entry.email || '';
            if (contactPhone) contactPhone.value = entry.phone || '';
        } else {
            hidden.value = '';
        }
    }

    input.addEventListener('change', function () {
        applyContactForName(this.value.trim());
    });

    // clear hidden if user clears input
    input.addEventListener('input', function () {
        if (!this.value.trim()) {
            hidden.value = '';
            if (contactEmail) contactEmail.value = '';
            if (contactPhone) contactPhone.value = '';
        }
    });
});
</script>

@endsection