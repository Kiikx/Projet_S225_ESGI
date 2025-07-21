<x-app-layout>
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-bold mb-6">Vue Kanban – {{ $project->name }}</h2>

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
                @php
                    $tasksInStatus = $project->tasks->where('status_id', $status->id);
                @endphp
                <div class="bg-gray-100 rounded shadow p-4">
                    <h3 class="text-xl font-semibold mb-4">{{ $status->name }}</h3>

                    <div class="task-column min-h-[100px]" data-status-id="{{ $status->id }}"
                        ondragover="event.preventDefault();" ondrop="handleDrop(event, {{ $status->id }})">
                        
                        @forelse($tasksInStatus as $task)
                            <div class="task bg-white p-3 mb-3 rounded shadow cursor-move" draggable="true"
                                data-id="{{ $task->id }}" ondragstart="handleDragStart(event)">
                                <p class="font-semibold">{{ $task->title }}</p>
                                <p class="text-sm text-gray-500">Assigné à : 
                                    @if($task->assignees->count())
                                        @foreach($task->assignees as $assignee)
                                            <span class="inline-block bg-blue-100 text-blue-800 px-1 py-0.5 rounded text-xs mr-1">{{ $assignee->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400">Personne</span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-400">
                                    @foreach ($task->categories as $category)
                                        <span class="inline-block bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded mr-1">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        @empty
                            <div class="bg-white border-2 border-dashed border-gray-200 rounded p-4 text-center">
                                <p class="text-gray-500 text-sm">📝 Aucune tâche</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if (!$status->is_protected && $tasksInStatus->isEmpty())
                        <form action="{{ route('projects.statuses.destroy', [$project, $status]) }}" method="POST"
                            onsubmit="return confirm('Supprimer cette colonne ?')" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm hover:underline">
                                ✕ Supprimer la colonne
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <script>
        let dragged;
        let originalColumn;

        function handleDragStart(e) {
            dragged = e.target;
            originalColumn = dragged.parentElement;
        }

        function handleDrop(e, statusId) {
            e.preventDefault();

            if (!dragged || !dragged.dataset.id) return;

            const taskId = dragged.dataset.id;
            const newColumn = e.currentTarget;
            
            // Ne rien faire si c'est la même colonne
            if (originalColumn === newColumn) return;

            // Déplacer visuellement la tâche
            newColumn.appendChild(dragged);
            
            // Supprimer le message "Aucune tâche" s'il existe
            const emptyMessage = newColumn.querySelector('.border-dashed');
            if (emptyMessage) {
                emptyMessage.remove();
            }
            
            // Ajouter le message "Aucune tâche" dans l'ancienne colonne si elle devient vide
            if (originalColumn.children.length === 0) {
                originalColumn.innerHTML = `
                    <div class="bg-white border-2 border-dashed border-gray-200 rounded p-4 text-center">
                        <p class="text-gray-500 text-sm">📝 Aucune tâche</p>
                    </div>
                `;
            }

            // Envoyer la requête au serveur
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
                        // Remettre la tâche à sa place originale en cas d'erreur
                        originalColumn.appendChild(dragged);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert("Erreur de connexion.");
                    originalColumn.appendChild(dragged);
                });
        }
    </script>
</x-app-layout>
