<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Tâches pour le projet : {{ $project->name }}</h2>

        <a href="{{ route('projects.tasks.create', $project) }}"
            class="inline-block mb-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Nouvelle tâche
        </a>

        @if($project->tasks->count())
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 text-left">Titre</th>
                        <th class="border p-2 text-left">Assignée à</th>
                        <th class="border p-2 text-left">Créateur</th>
                        <th class="border p-2 text-left">Catégories</th>
                        <th class="border p-2 text-left">Statut</th>
                        <th class="border p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->tasks as $task)
                        <tr class="border-b">
                            <td class="border p-2">{{ $task->title }}</td>

                            <td class="border p-2">
                                @if($task->assignees->count())
                                    @foreach($task->assignees as $assignee)
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm mr-1">
                                            {{ $assignee->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">Non assignée</span>
                                @endif
                            </td>

                            <td class="border p-2">
                                {{ $task->creator?->name ?? 'Inconnu' }}
                            </td>

                            <td class="border p-2">
                           
                                @if($task->categories->count())
                                    @foreach($task->categories as $category)
                                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                @else
                                    Aucune
                                @endif
                            </td>
                            <td class="border p-2">
                                <span class="px-2 py-1 rounded text-sm 
                                    @if($task->status?->name === 'Fait') bg-green-100 text-green-800
                                    @elseif($task->status?->name === 'En cours') bg-yellow-100 text-yellow-800  
                                    @elseif($task->status?->name === 'Annulé') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $task->status?->name ?? 'À faire' }}
                                </span>
                            </td>

                            <td class="border p-2 space-x-2">
                                <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline">Modifier</a>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Supprimer cette tâche ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Supprimer
                                    </button>
                                </form>

                                @if($task->status?->name !== 'Fait')
                                    @php
                                        $doneStatus = $project->statuses->where('name', 'Fait')->first();
                                    @endphp
                                    @if($doneStatus)
                                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_id" value="{{ $doneStatus->id }}">
                                            <button type="submit" class="text-green-600 hover:underline">
                                                Marquer comme terminée
                                            </button>
                                        </form>
                                    @endif
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