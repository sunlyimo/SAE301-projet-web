<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CartoRun - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased">
    <nav class="bg-white py-4 px-6 border-b border-gray-100">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
            </div>

            <div class="flex space-x-2">
                <a href="/" class="px-4 py-2 bg-gray-400 text-white rounded-md text-sm font-medium">Accueil</a>
                <a href="#" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">Contact</a>
                <a href="#" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">A propos</a>
                <a href="/raids" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">Raids</a>
                <a href="/courses" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">Courses</a>
                <a href="/clubs" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">Clubs</a>
            </div>

            <div class="flex space-x-2">
                <a href="/login" class="px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-200">Se connecter</a>
                <a href="/register" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-black">S'inscrire</a>
            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>