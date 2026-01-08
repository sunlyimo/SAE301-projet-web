
@extends('layouts.app')
<?php
use Illuminate\Support\Facades\DB;
?>

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <i class="fas fa-person-running text-3xl text-gray-900"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Modification du raid</h1>
                        <p class="text-sm text-gray-600">Configuration de l'événement et informations de contact</p>
                    </div>
                </div>
                    @if($errors->any())
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 text-red-800">
                        <ul class="list-disc pl-5 mb-0">
                            @foreach($errors->all() as $error)
                            <li class="flex items-start gap-2"><i class="fas fa-triangle-exclamation mt-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ isset($raid) ? route('raids.update', ['raid_id' => $raid->RAI_ID]) : route('raids.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center justify-center rounded-full bg-green-600 text-white px-3 py-1 text-sm font-semibold">1</span>
                            <h5 class="mb-0 font-bold text-gray-900">Informations générales</h5>
                        </div>

                        <div class="mb-4">
                            <label for="RAI_NOM" class="block text-sm font-medium text-gray-700">Nom de l'événement</label>
                            <input type="text" name="RAI_NOM" id="RAI_NOM" value="{{ old('RAI_NOM', $raid->RAI_NOM ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-2 focus:ring-green-600 p-3 @error('RAI_NOM') border-red-400 @enderror" placeholder="Nom du raid">
                            @error('RAI_NOM') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="RAI_LIEU" class="block text-sm font-medium text-gray-700">Lieu de départ</label>
                            <input type="text" name="RAI_LIEU" id="RAI_LIEU" value="{{ old('RAI_LIEU', $raid->RAI_LIEU ?? '') }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-2 focus:ring-green-600 p-3" placeholder="Lieu">
                        </div>

                        <div class="flex items-center gap-3 mb-4 mt-6">
                            <span class="inline-flex items-center justify-center rounded-full bg-green-600 text-white px-3 py-1 text-sm font-semibold">2</span>
                            <h5 class="mb-0 font-bold text-gray-900">Dates clés</h5>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 mb-4">
                            <div>
                                <div class="p-4 border rounded-lg bg-gray-50">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase">Événement</label>
                                    <div class="mt-2">
                                        <label class="block text-sm text-gray-600">Début</label>
                                        <input type="datetime-local" name="RAI_RAID_DATE_DEBUT" value="{{ old('RAI_RAID_DATE_DEBUT', $raid->RAI_RAID_DATE_DEBUT ? date('Y-m-d\TH:i', strtotime($raid->RAI_RAID_DATE_DEBUT)) : '') }}" class="mt-1 block w-full rounded-md border-gray-200 p-2">
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-sm text-gray-600">Fin</label>
                                        <input type="datetime-local" name="RAI_RAID_DATE_FIN" value="{{ old('RAI_RAID_DATE_FIN', $raid->RAI_RAID_DATE_FIN ? date('Y-m-d\TH:i', strtotime($raid->RAI_RAID_DATE_FIN)) : '') }}" class="mt-1 block w-full rounded-md border-gray-200 p-2">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="p-4 border rounded-lg bg-gray-50">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase">Inscriptions</label>
                                    <div class="mt-2">
                                        <label class="block text-sm text-gray-600">Ouverture</label>
                                        <input type="datetime-local" name="RAI_INSCRI_DATE_DEBUT" value="{{ old('RAI_INSCRI_DATE_DEBUT', $raid->RAI_INSCRI_DATE_DEBUT ? date('Y-m-d\TH:i', strtotime($raid->RAI_INSCRI_DATE_DEBUT)) : '') }}" class="mt-1 block w-full rounded-md border-gray-200 p-2">
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-sm text-gray-600">Clôture</label>
                                        <input type="datetime-local" name="RAI_INSCRI_DATE_FIN" value="{{ old('RAI_INSCRI_DATE_FIN', $raid->RAI_INSCRI_DATE_FIN ? date('Y-m-d\TH:i', strtotime($raid->RAI_INSCRI_DATE_FIN)) : '') }}" class="mt-1 block w-full rounded-md border-gray-200 p-2">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-4 mt-6">
                            <span class="inline-flex items-center justify-center rounded-full bg-green-600 text-white px-3 py-1 text-sm font-semibold">3</span>
                            <h5 class="mb-0 font-bold text-gray-900">Responsables</h5>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 mb-4">
                            <div>
                                <label for="CLU_ID" class="block text-sm font-medium text-gray-700">Club Organisateur</label>
                                <select name="CLU_ID" id="CLU_ID" class="mt-1 block w-full rounded-md border-gray-200 p-3">
                                    <option value="">Choisir un club...</option>
                                    @foreach($clubs ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ old('CLU_ID', $raid->CLU_ID ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="responsable_input" class="block text-sm font-medium text-gray-700">Responsable</label>
                                <input list="responsable_list" id="responsable_input" name="responsable_name" class="mt-1 block w-full rounded-md border-gray-200 p-3" placeholder="Rechercher un membre..." autocomplete="off" value="{{ old('responsable_name', (collect($responsables ?? [])->firstWhere('UTI_ID', $raid->UTI_ID ?? null)->name) ?? '') }}">
                                <datalist id="responsable_list">
                                    @foreach($responsables ?? [] as $resp)
                                        <option value="{{ $resp->name }}"></option>
                                    @endforeach
                                </datalist>
                                <input type="hidden" name="UTI_ID" id="UTI_ID" value="{{ old('UTI_ID', $raid->UTI_ID ?? '') }}">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-4 mt-6">
                            <span class="inline-flex items-center justify-center rounded-full bg-green-600 text-white px-3 py-1 text-sm font-semibold">4</span>
                            <h5 class="mb-0 font-bold text-gray-900">Contact</h5>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 mb-4">
                            <div>
                                <label for="RAI_CONTACT" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="RAI_CONTACT" id="RAI_CONTACT" value="{{ old('RAI_CONTACT', $raid->RAI_CONTACT ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-2 focus:ring-green-600 p-3 @error('RAI_CONTACT') border-red-400 @enderror" readonly placeholder="Email">
                                @error('RAI_CONTACT') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="RAI_WEB" class="block text-sm font-medium text-gray-700">Site internet (facultatif)</label>
                                <input type="url" name="RAI_WEB" id="RAI_WEB" value="{{ old('RAI_WEB', $raid->RAI_WEB ?? '') }}" class="mt-1 block w-full rounded-md border-gray-200 p-3" placeholder="Site web">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700">IMAGE DE COUVERTURE</label>
                            <div class="mt-2">
                                <input type="file" name="RAI_IMAGE" class="block w-full text-sm text-gray-700">
                            </div>
                        </div>

                        <div class="grid gap-3 md:grid-cols-3">
                            <div class="md:col-span-2">
                                <button type="submit" class="w-full bg-gray-900 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-plus-circle"></i> Enregistrer le Raid
                                </button>
                            </div>
                            <div>
                                <a href="#" class="w-full inline-block text-center border border-gray-300 text-gray-700 py-3 rounded-lg">Annuler</a>
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