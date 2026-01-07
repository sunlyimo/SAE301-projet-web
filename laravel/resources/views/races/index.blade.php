@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto my-12 p-6">
    <h2 class="text-3xl font-bold mb-8 text-gray-800">Liste des Courses</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-full uppercase">
                        ID: {{ $course->RAI_ID }}-{{ $course->COU_ID }}
                    </span>
                    <span class="text-lg font-bold text-green-600">{{ $course->COU_PRIX }} €</span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $course->COU_NOM }}</h3>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <p class="flex items-center">
                        <span class="mr-2"></span> {{ $course->COU_LIEU }}
                    </p>
                    <p class="flex items-center">
                        <span class="mr-2"></span> {{ \Carbon\Carbon::parse($course->COU_DATE_DEBUT)->format('d/m/Y H:i') }}
                    </p>
                    <p class="flex items-center">
                        <span class="mr-2"></span> Max: {{ $course->COU_PARTICIPANT_MAX }} participants
                    </p>
                </div>

                <button class="w-full mt-6 bg-black text-white py-3 rounded-xl font-bold hover:bg-green-600 transition-colors">
                    S'inscrire
                </button>
            </div>
        @endforeach
    </div>
</div>
@endsection