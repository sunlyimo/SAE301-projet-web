@extends('layouts.app')

@section('title', 'Club créé avec succès')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-green-800">Club créé avec succès !</h3>
                <p class="text-green-700 mt-1">Le club "{{ $club->CLU_NOM }}" a été ajouté à votre système.</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-blue-700 mb-3">
                        @if($club->responsable)
                            Un email d'invitation a été envoyé au responsable <strong>{{ $club->responsable->UTI_PRENOM }} {{ $club->responsable->UTI_NOM }}</strong>.
                        @elseif(!empty($invitedUser))
                            Un email d'invitation a été envoyé à <strong>{{ $invitedUser->UTI_PRENOM }} {{ $invitedUser->UTI_NOM }}</strong> (utilisateur existant ou nouvel utilisateur créé).
                        @else
                            Un email d'invitation a été envoyé au responsable (utilisateur non précisé).
                        @endif
                    </p>
                    <button onclick="openMailbox()" id="open-mailbox-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                        Ouvrir la boîte mail
                    </button>
                    <p class="text-xs text-blue-600 mt-2" id="auto-open-message">
                        La boîte mail va s'ouvrir automatiquement dans quelques secondes...
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('clubs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Retour à la liste des clubs
            </a>
            @if($club->responsable)
                <span class="text-sm text-green-600">Statut : Responsable validé</span>
            @elseif(!empty($invitation))
                <span class="text-sm text-gray-500">Statut : En attente de validation du responsable</span>
            @endif
        </div>
    </div>
</div>

<script>
    function openMailbox() {
        const mailboxUrl = '{{ route("responsable.mailbox", ["club_id" => $club->CLU_ID, "token" => $token]) }}';
        const newWindow = window.open(mailboxUrl, '_blank');

        if (newWindow) {
            // La fenêtre s'est ouverte avec succès
            document.getElementById('auto-open-message').textContent = 'Boîte mail ouverte avec succès !';
            document.getElementById('open-mailbox-btn').textContent = 'Boîte mail ouverte ✓';
            document.getElementById('open-mailbox-btn').disabled = true;
            document.getElementById('open-mailbox-btn').classList.remove('bg-blue-600', 'hover:bg-blue-700');
            document.getElementById('open-mailbox-btn').classList.add('bg-green-600', 'cursor-not-allowed');
        } else {
            // Les popups sont bloqués
            document.getElementById('auto-open-message').innerHTML = '<span class="text-red-600">Les popups sont bloqués. <a href="' + mailboxUrl + '" target="_blank" class="underline hover:no-underline">Cliquez ici pour ouvrir manuellement</a></span>';
            document.getElementById('open-mailbox-btn').textContent = 'Popup bloqué - Lien ci-dessus';
            document.getElementById('open-mailbox-btn').disabled = true;
        }
    }

    // Tenter d'ouvrir automatiquement après un délai
    setTimeout(function() {
        openMailbox();
    }, 2000);

    // Déconnecter l'admin après l'ouverture du mail
    setTimeout(function() {
        document.getElementById('logout-form').submit();
    }, 4000);
</script>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection