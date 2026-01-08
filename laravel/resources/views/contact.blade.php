@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <div class="flex justify-center lg:justify-start">
            <img src="{{ asset('images/personne.jpg') }}" alt="Course d'orientation" class="w-full max-w-md rounded-lg shadow-lg object-cover">
        </div>

        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Une question ?</h1>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                @csrf

                @guest
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Votre nom"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="votre.email@exemple.fr"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                @endguest

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Décrivez-nous votre problème</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        placeholder="..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all resize-none @error('message') border-red-500 @enderror"
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong>Note importante :</strong> Ce formulaire est destiné aux questions et demandes d'assistance légitimes.
                        Tout usage abusif, frauduleux ou contraire aux bonnes pratiques sera signalé et pourra faire l'objet de poursuites.
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full md:w-auto px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md active:scale-95" style="margin-top: 20px"
                >
                    Envoyer
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
