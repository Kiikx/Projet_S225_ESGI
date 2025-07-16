<x-app-layout>
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-bold mb-6">Vue Kanban – {{ $project->title }}</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 mb-6">
            <h3 class="text-lg font-semibold mb-2">Ajouter une colonne personnalisée</h3>

            <form action="{{ route('projects.statuses.store', $project) }}" method="POST" class="flex gap-2 items-center">
                @csrf
                <input type="text" name="name" placeholder="Nom de la colonne" required
                    class="border p-2 rounded w-1/3">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Ajouter
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-{{ $project->statuses->count() }} gap-6">
            @foreach ($project->statuses as $status)
                <div class="bg-gray-100 rounded shadow p-4">
                    <h3 class="text-xl font-semibold mb-4">{{ $status->name }}</h3>

                    <div class="task-column min-h-[50px]" data-status-id="{{ $status->id }}"
                        ondragover="event.preventDefault();" ondrop="handleDrop(event, {{ $status->id }})">
                        @forelse($status->tasks as $task)
                            <div class="task bg-white p-3 mb-3 rounded shadow cursor-move" draggable="true"
                                data-id="{{ $task->id }}" ondragstart="handleDragStart(event)">
                                <p class="font-semibold">{{ $task->title }}</p>
                                <p class="text-sm text-gray-500">Assigné à : {{ $task->assignee?->name ?? 'Personne' }}
                                </p>
                                <p class="text-sm text-gray-400">
                                    @foreach ($task->categories as $category)
                                        <span
                                            class="inline-block bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded mr-1">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </p>

                            </div>
                        @empty
                            <p class="text-gray-400 italic">Aucune tâche</p>
                        @endforelse
                    </div>
                    @if (!in_array($status->name, ['À faire', 'En cours', 'Terminé']) && $status->tasks->isEmpty())
                        <form action="{{ route('projects.statuses.destroy', [$project, $status]) }}" method="POST"
                            onsubmit="return confirm('Supprimer cette colonne ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm hover:underline ml-2">
                                ✕
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <script>
        let dragged;

        function handleDragStart(e) {
            dragged = e.target;
        }

        function handleDrop(e, statusId) {
            e.preventDefault();

            if (!dragged || !dragged.dataset.id) return;

            const taskId = dragged.dataset.id;
            const column = e.currentTarget;

            column.appendChild(dragged);

            fetch(`/tasks/${taskId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status_id: statusId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        alert("Erreur lors de la mise à jour.");
                    }
                });
        }
    </script>
</x-app-layout>
