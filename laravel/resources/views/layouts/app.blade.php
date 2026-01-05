<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CartoRun - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased text-gray-900">
    <nav class="relative bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
                    <span class="text-2xl font-bold text-black tracking-tight">
                        CartoRun
                    </span>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    @php
                        $links = [
                            'Accueil' => '/',
                            'Contact' => '#',
                            'À propos' => '#',
                            'Raids' => '/raids',
                            'Courses' => '/courses',
                            'Clubs' => '/clubs'
                        ];
                    @endphp

                    @foreach($links as $name => $url)
                    <div class="relative group h-full flex items-center">
                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-black transition-colors duration-300 group-hover:text-blue-600">
                            {{ $name }}
                        </a>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center gap-6">
                    <a href="/login" class="text-sm font-semibold text-black hover:text-blue-600 transition-colors duration-300">
                        Se connecter
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-black text-white text-sm font-bold hover:bg-blue-600 transition-all duration-300 shadow-md active:scale-95">
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>