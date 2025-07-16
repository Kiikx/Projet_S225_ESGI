<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">
            Mes projets
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-end">
                <a href="{{ route('projects.create') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    + Nouveau projet
                </a>
            </div>

            @if($projects->count())
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <a href="{{ route('projects.show', $project) }}"
                           class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:shadow-md transition">
                            <h3 class="text-xl font-bold text-gray-800">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-500 mt-2">
                                Créé par : <span class="font-medium">{{ $project->owner->name }}</span>
                            </p>
                            <p class="text-sm text-gray-400 mt-1">ID Projet : {{ $project->id }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500">Aucun projet pour le moment.</div>
            @endif
        </div>
    </div>
</x-app-layout>
