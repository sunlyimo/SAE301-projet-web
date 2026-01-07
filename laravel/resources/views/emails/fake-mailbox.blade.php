<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte mail - {{ $invitedUser->UTI_EMAIL ?? ($club->responsable->UTI_EMAIL ?? 'Boîte') }}</title>
    <style>
        /* Styles Gmail-like */
        body {
            font-family: 'Google Sans', Roboto, RobotoDraft, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f6f8fc;
        }

        .gmail-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e8eaed;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(60,64,67,.3);
        }

        .gmail-logo {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 400;
            color: #202124;
        }

        .gmail-logo img {
            width: 32px;
            height: 32px;
            margin-right: 8px;
        }

        .gmail-user-info {
            display: flex;
            align-items: center;
            color: #5f6368;
            font-size: 14px;
        }

        .gmail-main {
            display: flex;
            height: calc(100vh - 65px);
        }

        .gmail-sidebar {
            width: 280px;
            background-color: #ffffff;
            border-right: 1px solid #e8eaed;
            padding: 8px 0;
        }

        .gmail-compose-btn {
            background-color: #c5221f;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            margin: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .gmail-compose-btn:hover {
            background-color: #b02825;
        }

        .gmail-menu-item {
            padding: 8px 24px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #202124;
            cursor: pointer;
        }

        .gmail-menu-item:hover {
            background-color: #f2f2f2;
        }

        .gmail-menu-item.active {
            background-color: #e8f0fe;
            border-left: 3px solid #1a73e8;
            padding-left: 21px;
        }

        .gmail-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
        }

        .gmail-toolbar {
            border-bottom: 1px solid #e8eaed;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gmail-search {
            flex: 1;
            position: relative;
        }

        .gmail-search input {
            width: 100%;
            padding: 8px 16px;
            border: 1px solid #dadce0;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
        }

        .gmail-search input:focus {
            border-color: #1a73e8;
            box-shadow: 0 1px 6px rgba(32,33,36,.28);
        }

        .gmail-email-list {
            flex: 1;
            overflow-y: auto;
        }

        .gmail-email-item {
            border-bottom: 1px solid #f1f3f4;
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .gmail-email-item:hover {
            background-color: #f2f2f2;
        }

        .gmail-email-item.unread {
            background-color: #fff;
            border-left: 3px solid #1a73e8;
        }

        .gmail-email-sender {
            font-weight: 500;
            color: #202124;
            margin-bottom: 2px;
        }

        .gmail-email-subject {
            font-weight: 400;
            color: #202124;
            margin-bottom: 2px;
        }

        .gmail-email-preview {
            color: #5f6368;
            font-size: 13px;
        }

        .gmail-email-content {
            flex: 1;
            border-left: 1px solid #e8eaed;
            display: flex;
            flex-direction: column;
        }

        .gmail-email-header {
            border-bottom: 1px solid #e8eaed;
            padding: 20px;
        }

        .gmail-email-subject-full {
            font-size: 18px;
            font-weight: 400;
            color: #202124;
            margin-bottom: 12px;
        }

        .gmail-email-meta {
            font-size: 12px;
            color: #5f6368;
            margin-bottom: 8px;
        }

        .gmail-email-body {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        .gmail-btn {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 4px;
        }

        .gmail-btn:hover {
            background-color: #1557b0;
        }

        .gmail-btn-primary {
            background-color: #1a73e8;
        }

        .gmail-btn-secondary {
            background-color: #34a853;
        }

        .gmail-btn-refuse {
            background-color: #ea4335;
            color: white;
            border: 1px solid #ea4335;
        }

        .gmail-btn-refuse:hover {
            background-color: #d33b2c;
        }

        .club-details-box {
            background-color: #f8f9fa;
            border: 1px solid #e8eaed;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
        }

        .club-details-box h3 {
            margin-top: 0;
            color: #202124;
            font-size: 16px;
        }

        .club-details-box ul {
            margin: 8px 0;
            padding-left: 20px;
        }

        .club-details-box li {
            margin-bottom: 4px;
            color: #5f6368;
        }
    </style>
</head>
<body>
    <div class="gmail-header">
        <div class="gmail-logo">
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTI0IDEwLjY2NWMwLS44MjUtLjY3NS0xLjUtMS41LTEuNWgtMTBjLS44MjUgMC0xLjUuNjc1LTEuNSAxLjV2MTAuNWMwIC44MjUuNjc1IDEuNSAxLjUgMS41aDEwYzguMjUgMCAxLjUtLjY3NSAxLjUtMS41VjEwLjY2NXoiIGZpbGw9IiNFQTQzMzUiLz4KPHBhdGggZD0iTTExIDExaDEwdjIuNUgxMXYtMi41eiIgZmlsbD0iI0ZCRkIwMCIvPgo8L3N2Zz4K" alt="Mail">
            Boîte de réception
        </div>
        <div class="gmail-user-info">
            {{ $invitedUser->UTI_EMAIL ?? ($club->responsable->UTI_EMAIL ?? 'noreply@clubmanagement.com') }}
        </div>
    </div>

    <div class="gmail-main">
        <div class="gmail-sidebar">
            <button class="gmail-compose-btn">
                <span style="margin-right: 8px;">+</span>
                Nouveau message
            </button>
            <div class="gmail-menu-item active">
                <span style="margin-right: 12px;">📬</span>
                Boîte de réception
            </div>
            <div class="gmail-menu-item">
                <span style="margin-right: 12px;">📤</span>
                Envoyés
            </div>
            <div class="gmail-menu-item">
                <span style="margin-right: 12px;">📝</span>
                Brouillons
            </div>
        </div>

        <div class="gmail-content">
            <div class="gmail-toolbar">
                <div class="gmail-search">
                    <input type="text" placeholder="Rechercher dans la messagerie">
                </div>
            </div>

            <div class="gmail-email-list">
                <div class="gmail-email-item unread">
                    <div style="flex: 1;">
                        <div class="gmail-email-sender">
                            Club Management System
                            <span style="float: right; font-size: 12px; color: #5f6368;">{{ \Carbon\Carbon::now('Europe/Paris')->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="gmail-email-subject">
                            Invitation a rejoindre le club "{{ $club->CLU_NOM }}"
                        </div>
                        <div class="gmail-email-preview">
                            Vous avez ete nomme responsable du club {{ $club->CLU_NOM }}...
                        </div>
                    </div>
                </div>
            </div>

            <div class="gmail-email-content">
                <div class="gmail-email-header">
                    <div class="gmail-email-subject-full">Invitation a rejoindre le club "{{ $club->CLU_NOM }}"</div>
                    <div class="gmail-email-meta">
                        <strong>De:</strong> Club Management System &lt;admin@clubmanagement.com&gt;<br>
                        <strong>A:</strong> {{ $invitedUser->UTI_EMAIL ?? ($club->responsable->UTI_EMAIL ?? 'noreply@clubmanagement.com') }}<br>
                        <strong>Date:</strong> {{ \Carbon\Carbon::now('Europe/Paris')->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="gmail-email-body">
                    <p>Bonjour {{ $invitedUser->UTI_PRENOM ?? $invitedUser->UTI_NOM ?? 'Utilisateur' }},</p>

                    <p>Vous avez ete invite(e) a devenir responsable du club <strong>"{{ $club->CLU_NOM }}"</strong>.</p>

                    <div class="club-details-box">
                        <h3>Details du club :</h3>
                        <ul>
                            <li><strong>Nom du club :</strong> {{ $club->CLU_NOM }}</li>
                            <li><strong>Adresse :</strong> {{ $club->CLU_RUE }}, {{ $club->CLU_CODE_POSTAL }} {{ $club->CLU_VILLE }}</li>
                            <li><strong>Votre nom d'utilisateur :</strong> {{ $invitedUser->UTI_NOM_UTILISATEUR ?? 'à définir' }}</li>
                            <li><strong>Votre email :</strong> {{ $invitedUser->UTI_EMAIL ?? 'à définir' }}</li>
                        </ul>
                    </div>

                    <p>Veuillez choisir entre accepter ou refuser d'être responsable du club :</p>

                    <div class="email-actions">
                        @if(!empty($invitation))
                            @if(empty($invitedUser->UTI_MOT_DE_PASSE))
                                <!-- New user: send them to the registration finalization page so they don't need to log in -->
                                <a href="{{ route('responsable.register', ['club_id' => $club->CLU_ID, 'token' => $token]) }}" class="gmail-btn gmail-btn-primary" style="margin-bottom: 10px; display:inline-block; text-decoration:none;">
                                    Compléter l'inscription et accepter
                                </a>

                                <form method="POST" action="{{ route('responsable.refuse', ['club_id' => $club->CLU_ID, 'token' => $token]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="gmail-btn gmail-btn-refuse" style="margin-bottom: 10px; margin-left: 10px;">
                                        Refuser
                                    </button>
                                </form>
                            @else
                                <!-- Existing user: use the accept flow which requires authentication -->
                                <a href="{{ route('responsable.invitation.accept.login', ['club_id' => $club->CLU_ID, 'user_id' => $invitedUser->UTI_ID, 'token' => $token]) }}" class="gmail-btn gmail-btn-primary" style="margin-bottom: 10px; display:inline-block; text-decoration:none;">
                                    Accepter l'invitation
                                </a>

                                <form method="POST" action="{{ route('responsable.invitation.refuse', ['club_id' => $club->CLU_ID, 'user_id' => $invitedUser->UTI_ID, 'token' => $token]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="gmail-btn gmail-btn-refuse" style="margin-bottom: 10px; margin-left: 10px;">
                                        Refuser
                                    </button>
                                </form>
                            @endif

                        @else
                            <form method="POST" action="{{ route('responsable.quick-validate', ['club_id' => $club->CLU_ID, 'token' => $token]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="gmail-btn gmail-btn-primary" style="margin-bottom: 10px;">
                                    Accepter d'être nommé responsable
                                </button>
                            </form>
                            <form method="POST" action="{{ route('responsable.refuse', ['club_id' => $club->CLU_ID, 'token' => $token]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="gmail-btn gmail-btn-refuse" style="margin-bottom: 10px; margin-left: 10px;">
                                    Refuser
                                </button>
                            </form>
                        @endif
                    </div>

                    <p>Cette invitation expire dans 24 heures.</p>

                    <p>Cordialement,<br>
                    L'équipe Club Management System</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>