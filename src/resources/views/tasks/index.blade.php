<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Tâches pour le projet : {{ $project->title }}</h2>

        <a href="{{ route('projects.tasks.create', $project) }}"
           class="inline-block mb-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Nouvelle tâche
        </a>

        @if($project->tasks->count())
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 text-left">Titre</th>
                        <th class="border p-2 text-left">Statut</th>
                        <th class="border p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->tasks as $task)
                        <tr class="border-b">
                            <td class="border p-2">{{ $task->title }}</td>
                            <td class="border p-2 capitalize">{{ $task->status ?? 'à faire' }}</td>
                            <td class="border p-2 space-x-2">
                                <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline">
                                    Modifier
                                </a>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Supprimer cette tâche ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Supprimer
                                    </button>
                                </form>

                                @if($task->status !== 'done')
                                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" class="text-green-600 hover:underline">
                                            Marquer comme terminée
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune tâche pour ce projet.</p>
        @endif
    </div>
</x-app-layout>
