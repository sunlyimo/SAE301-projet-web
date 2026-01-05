<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CartoRun - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased text-gray-900">
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20"> <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto"> <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                        CartoRun
                    </span>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="/" class="px-4 py-2 rounded-full bg-gray-100 text-gray-900 font-semibold text-sm transition-all shadow-sm">Accueil</a>
                    <a href="#" class="px-4 py-2 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-50 text-sm font-medium transition-all">Contact</a>
                    <a href="#" class="px-4 py-2 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-50 text-sm font-medium transition-all">À propos</a>
                    <a href="/raids" class="px-4 py-2 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-50 text-sm font-medium transition-all">Raids</a>
                    <a href="/courses" class="px-4 py-2 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-50 text-sm font-medium transition-all">Courses</a>
                    <a href="/clubs" class="px-4 py-2 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-50 text-sm font-medium transition-all">Clubs</a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="/login" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">Se connecter</a>
                    <a href="/register" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition-all shadow-lg hover:shadow-gray-200 active:scale-95">
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>