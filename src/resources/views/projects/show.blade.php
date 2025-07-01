<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Détails du projet -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $project->title }}</h1>
            <p class="text-gray-600">{{ $project->description ?? 'Aucune description.' }}</p>

            <div class="mt-4 text-sm text-gray-500">
                Créé par : {{ $project->owner->name ?? 'Inconnu' }}
            </div>

            <!-- BOUTONS : Voir toutes les tâches + Ajouter une tâche -->
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('projects.tasks.index', $project) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    📋 Voir toutes les tâches
                </a>

                <a href="{{ route('projects.tasks.create', $project) }}"
                   class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    ➕ Ajouter une tâche
                </a>
            </div>
        </div>

        <!-- Section optionnelle des tâches récentes -->
        @if($project->tasks->count())
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Tâches récentes</h2>

                <ul class="space-y-2">
                    @foreach($project->tasks->take(5) as $task)
                        <li class="flex justify-between items-center bg-gray-50 border rounded px-4 py-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $task->title }}</p>
                                <p class="text-sm text-gray-500">{{ $task->status }}</p>
                            </div>

                            <a href="{{ route('tasks.edit', $task) }}"
                               class="text-blue-500 hover:underline text-sm">
                                Modifier
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-gray-500 text-center">
                Ce projet n’a pas encore de tâches.
            </div>
        @endif
    </div>
</x-app-layout>
