@extends('layouts.app')

@section('title', 'Invitation Responsable')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-xl font-bold mb-4">Invitation</h1>

    <p class="mb-2">Bonjour {{ $user->UTI_PRENOM ?? $user->UTI_NOM }},</p>

    <p class="mb-4">Vous avez été invité(e) à devenir responsable du club "<strong>{{ $club->CLU_NOM }}</strong>".</p>

    <p class="mb-4">Voulez-vous accepter cette invitation ?</p>

    <form method="POST" action="{{ route('responsable.invitation.accept', ['club_id' => $club->CLU_ID, 'user_id' => $user->UTI_ID, 'token' => $token]) }}" style="display:inline;">
        @csrf
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Accepter</button>
    </form>

    @if(session('info'))
        <p class="text-blue-600 mt-3">{{ session('info') }}</p>
    @endif

    <p class="mt-2 text-sm text-gray-600">Si vous n'êtes pas connecté, vous serez redirigé vers la page de connexion puis pourrez revenir pour accepter.</p>

    <form method="POST" action="{{ route('responsable.invitation.refuse', ['club_id' => $club->CLU_ID, 'user_id' => $user->UTI_ID, 'token' => $token]) }}" style="margin-top:10px;">
        @csrf
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Refuser</button>
    </form>
</div>
@endsection