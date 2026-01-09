@extends('layouts.app')

@section('title', 'Mes adhérents')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <h1 class="text-2xl font-bold mb-4">Adhérents du club : {{ $club->CLU_NOM }}</h1>

    @if($members->isEmpty())
        <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-md">Aucun adhérent trouvé pour ce club.</div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Liste des adhérents</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Licence</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $m)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $m->UTI_NOM }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $m->UTI_PRENOM }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $m->UTI_EMAIL }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $m->UTI_LICENCE }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </dl>
            </div>
        </div>
    @endif

</div>
@endsection
