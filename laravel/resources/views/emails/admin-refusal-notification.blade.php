<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification - Refus de responsable</title>
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
            padding: 8px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .gmail-toolbar-btn {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            border-radius: 50%;
        }

        .gmail-toolbar-btn:hover {
            background-color: #f1f3f4;
        }

        .gmail-email-list {
            flex: 1;
            overflow-y: auto;
        }

        .gmail-email-item {
            border-bottom: 1px solid #e8eaed;
            padding: 12px 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .gmail-email-item:hover {
            background-color: #f2f2f2;
        }

        .gmail-email-item.unread {
            background-color: #fff3e0;
            border-left: 3px solid #ff9800;
        }

        .gmail-email-sender {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .gmail-email-subject {
            font-weight: 500;
            margin-bottom: 2px;
        }

        .gmail-email-preview {
            color: #5f6368;
            font-size: 14px;
        }

        .gmail-email-content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        .gmail-email-header {
            border-bottom: 1px solid #e8eaed;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .gmail-email-subject-full {
            font-size: 20px;
            font-weight: 400;
            margin-bottom: 8px;
        }

        .gmail-email-meta {
            color: #5f6368;
            font-size: 14px;
            line-height: 1.5;
        }

        .gmail-email-body {
            line-height: 1.5;
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
            <img src="https://www.gstatic.com/images/branding/product/1x/gmail_2020q4_32dp.png" alt="Gmail">
            Gmail
        </div>
        <div class="gmail-user-info">
            admin@clubmanagement.com
        </div>
    </div>

    <div class="gmail-main">
        <div class="gmail-sidebar">
            <button class="gmail-compose-btn">
                <span>✏️</span> Rédiger
            </button>
            <div class="gmail-menu-item active">
                Boîte de réception
            </div>
            <div class="gmail-menu-item">
                Éléments envoyés
            </div>
            <div class="gmail-menu-item">
                Brouillons
            </div>
        </div>

        <div class="gmail-content">
            <div class="gmail-toolbar">
                <button class="gmail-toolbar-btn">☑️</button>
                <button class="gmail-toolbar-btn">🔄</button>
                <button class="gmail-toolbar-btn">🗑️</button>
            </div>

            <div class="gmail-email-content">
                <div class="gmail-email-header">
                    <div class="gmail-email-subject-full">Refus de nomination - Club "{{ $club->CLU_NOM }}"</div>
                    <div class="gmail-email-meta">
                        <strong>De:</strong> Système de gestion des clubs &lt;system@clubmanagement.com&gt;<br>
                        <strong>A:</strong> admin@clubmanagement.com<br>
                        <strong>Date:</strong> {{ \Carbon\Carbon::now('Europe/Paris')->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="gmail-email-body">
                    <p>Bonjour Administrateur,</p>

                    <p>Le responsable désigné pour le club <strong>"{{ $club->CLU_NOM }}"</strong> a refusé la nomination.</p>

                    <div class="club-details-box">
                        <h3>Détails du club refusé :</h3>
                        <ul>
                            <li><strong>Nom du club :</strong> {{ $club->CLU_NOM }}</li>
                            <li><strong>Adresse :</strong> {{ $club->CLU_RUE }}, {{ $club->CLU_CODE_POSTAL }} {{ $club->CLU_VILLE }}</li>
                            <li><strong>Responsable qui a refusé :</strong> {{ $club->responsable->UTI_NOM }} {{ $club->responsable->UTI_PRENOM }} ({{ $club->responsable->UTI_EMAIL }})</li>
                        </ul>
                    </div>

                    <p>Pour recréer le club avec un nouveau responsable, veuillez cliquer sur le bouton ci-dessous :</p>

                    <div class="email-actions">
                        <form method="POST" action="{{ route('admin.recreate-club', ['club_id' => $club->CLU_ID, 'token' => $token]) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="gmail-btn gmail-btn-primary">
                                Recréer le club
                            </button>
                        </form>
                    </div>

                    <p>Cordialement,<br>
                    Le système de gestion des clubs</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>