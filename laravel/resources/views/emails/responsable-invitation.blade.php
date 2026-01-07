<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation responsable</title>
</head>
<body>
    <p>Bonjour {{ $user->UTI_PRENOM ?? $user->UTI_NOM }},</p>

    <p>Vous avez été invité(e) à devenir responsable du club "{{ $club->CLU_NOM }}".</p>

    <p>Pour accepter l'invitation, cliquez ici :
        @if(!empty($isNewUser))
            <a href="{{ route('responsable.register', ['club_id' => $club->CLU_ID, 'token' => $token]) }}">Compléter l'inscription et accepter</a>
        @else
            <a href="{{ route('responsable.invitation.accept.login', ['club_id' => $club->CLU_ID, 'user_id' => $user->UTI_ID, 'token' => $token]) }}">Accepter l'invitation</a>
        @endif
    </p>

    <p>Si vous souhaitez refuser, cliquez ici :
        <form method="POST" action="{{ route('responsable.invitation.refuse', ['club_id' => $club->CLU_ID, 'user_id' => $user->UTI_ID, 'token' => $token]) }}" style="display:inline;">
            @csrf
            <button type="submit">Refuser</button>
        </form>
    </p>

    <p>Merci,<br>Le système de gestion des clubs</p>
</body>
</html>