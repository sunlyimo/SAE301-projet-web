<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CartoRun - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<body class="bg-white font-sans antialiased text-gray-900 flex flex-col min-h-screen">
    <nav class="relative bg-white border-b border-gray-100 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">

                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-900 hover:text-green-600 hover:bg-gray-100 focus:outline-none transition-colors">
                        <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
                        <span class="text-2xl font-bold text-black tracking-tight">
                            CartoRun
                        </span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    @php
                    $links = [
                        'Accueil' => '/',
                        'Clubs' => '/clubs',
                        'Raids' => '/raids',
                        'Contact' => '/contact',
                        'À propos' => '/about',
                        'Historique' => '/profile#historique'
                    ];
                    
                    use Illuminate\Support\Facades\DB;
                    
                    if (auth()->check() && DB::table('VIK_RESPONSABLE_CLUB')->where('UTI_ID', auth()->id())->exists()) {
                        $links['Créer un raid'] = '/raids/create';
                    }
                    
                    if (auth()->check()) {
                        $raid = DB::table('vik_raid')->where('UTI_ID', auth()->id())->first();
                        if ($raid) {
                            $links['Mes Raids'] = '/my-raids';
                        }
                        
                        $course = DB::table('vik_course')->where('UTI_ID', auth()->id())->first();
                        if ($course) {
                            $links['Mes Courses'] = '/my-courses';
                        }
                    }
                    @endphp

                    @foreach($links as $name => $url)
                    <div class="relative group h-full flex items-center">
                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-black transition-colors duration-300 group-hover:text-green-600">
                            {{ $name }}
                        </a>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-green-600 transition-all duration-300 group-hover:w-full"></span>
                    </div>
                    @endforeach
                </div>

                <div class="hidden md:flex items-center gap-6">
                    @guest
                    <a href="/login" class="text-sm font-semibold text-black hover:text-green-600 transition-colors duration-300">
                        Se connecter
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-black text-white text-sm font-bold hover:bg-green-600 transition-all duration-300 shadow-md active:scale-95">
                        S'inscrire
                    </a>
                    @endguest

                    @auth
                    <a href="/profile" class="text-sm font-semibold text-black hover:text-green-600 transition-colors duration-300">
                        Voir mon profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-black text-white text-sm font-bold hover:bg-green-600 transition-all duration-300 shadow-md active:scale-95 cursor-pointer">
                            Se déconnecter
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="md:hidden border-t border-gray-100"
        @click.away="mobileMenuOpen = false">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white shadow-lg">
            @foreach($links as $name => $url)
            <a href="{{ $url }}" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-green-600 hover:bg-gray-50 transition-colors">
                {{ $name }}
            </a>
            @endforeach

            <div class="border-t border-gray-200 my-2"></div>

            @guest
            <a href="/login" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-green-600 hover:bg-gray-50 transition-colors">
                Se connecter
            </a>
            <a href="/register" class="block mx-3 my-2 px-3 py-2 rounded-xl text-base font-bold text-white bg-black hover:bg-green-600 transition-colors text-center shadow-md">
                S'inscrire
            </a>
            @endguest

            @auth
            <a href="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-green-600 hover:bg-gray-50 transition-colors">
                Voir mon profil
            </a>
            <a href="/profile#historique" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-green-600 hover:bg-gray-50 transition-colors">
                Historique
            </a>
            <form method="POST" action="{{ route('logout') }}" class="px-3">
                @csrf
                <button type="submit" class="w-full my-2 px-3 py-2 rounded-xl text-base font-bold text-white bg-black hover:bg-green-600 transition-colors shadow-md text-left">
                    Se déconnecter
                </button>
            </form>
            @endauth
        </div>
        </div>
    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-gray-200 text-gray-900 py-12" style="padding: 50px 0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="flex flex-col items-start flex-shrink-0">
                    <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto">
                    </a>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-900 hover:text-green-600 transition-colors duration-300" aria-label="Instagram">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-900 hover:text-green-600 transition-colors duration-300" aria-label="Facebook">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="flex-1 flex justify-center">
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-gray-900">Liens utiles</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="https://orienteering.sport" target="_blank" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                    Fédération Internationale d'Orientation (IOF)
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/c/IOFOrienteering/videos" target="_blank" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                    Chaîne Youtube de l'IOF
                                </a>
                            </li>
                            <li>
                                <a href="https://www.ffcorientation.fr" target="_blank" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                    Fédération Française de Course d'Orientation (FFCO)
                                </a>
                            </li>
                            <li>
                                <a href="https://cn.ffcorientation.fr/classement/" target="_blank" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                    Classement national
                                </a>
                            </li>
                            <li>
                                <a href="https://liguenormandiecoursedorientation.fr" target="_blank" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                    Ligue de Normandie de Course d'Orientation (LNCO)
                                </a>
                            </li>
                            <li>
                                <a href="http://www.cdco14.fr" target="_blank" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                    Comité Départemental de Course d'Orientation (CDCO)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="flex-shrink-0 min-w-[250px]">
                    <h3 class="text-lg font-bold mb-4 text-gray-900">Nos contacts</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                            <a href="mailto:association@cartorun.fr" class="text-gray-900 hover:text-green-600 transition-colors duration-300">
                                association@cartorun.fr
                            </a>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                            <span>Caen 14000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    @if(session('welcome'))
    <div id="welcomeNotif" class="fixed top-4 right-4 z-50 bg-white rounded-lg shadow-2xl p-4 max-w-sm transform transition-all animate-slide-in border-l-4 border-green-600">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">Connexion réussie !</p>
                <p class="text-sm text-gray-600">
                    Bonjour <span class="font-bold text-green-600">{{ session('welcome') }}</span>
                </p>
            </div>
            <button onclick="closeWelcomeNotif()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>

    <script>
        function closeWelcomeNotif() {
            const notif = document.getElementById('welcomeNotif');
            notif.style.transform = 'translateX(100%)';
            notif.style.opacity = '0';
            setTimeout(() => {
                notif.style.display = 'none';
            }, 300);
        }

        setTimeout(() => {
            closeWelcomeNotif();
        }, 2500);
    </script>
    @endif

</body>

</html>