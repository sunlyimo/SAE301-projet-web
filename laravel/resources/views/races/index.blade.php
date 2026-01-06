<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des courses</title>
    <style>table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:6px}</style>
</head>
<body>
    <h1>Liste des courses</h1>

    @if ($races->isEmpty())
        <p>Aucune course trouvée.</p>
    @else
    <table>
        <thead>
            <tr>
                @foreach(array_keys($races->first()->getAttributes()) as $key)
                    <th>{{ $key }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($races as $race)
                <tr>
                    @foreach($race->getAttributes() as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
