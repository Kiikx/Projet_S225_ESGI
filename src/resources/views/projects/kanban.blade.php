<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-6">Vue Kanban – {{ $project->title }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(['todo' => 'À faire', 'doing' => 'En cours', 'done' => 'Terminée'] as $status => $label)
                @php
                    $tasks = $tasksByStatus[$status];
                @endphp

                <div class="bg-gray-100 rounded p-4 shadow min-h-[200px]">
                    <h3 class="text-lg font-semibold mb-4">{{ $label }}</h3>

                    <div id="{{ $status }}" data-status="{{ $status }}">
                        @foreach($tasks as $task)
                            <div class="bg-white rounded shadow p-3 mb-4" data-id="{{ $task->id }}">
                                <h4 class="font-bold">{{ $task->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $task->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Assignée à : {{ $task->assignee->name ?? 'Non assignée' }}<br>
                                    Catégories :
                                    @foreach($task->categories as $category)
                                        <span class="inline-block bg-blue-200 text-blue-800 px-2 py-0.5 rounded text-xs mr-1">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <p class="no-tasks text-sm text-gray-500 italic mt-2 {{ $tasks->isEmpty() ? '' : 'hidden' }}">
                        Aucune tâche.
                    </p>
                </div>
            @endforeach
        </div>

        <a href="{{ route('projects.show', $project) }}" class="mt-6 inline-block text-blue-600 hover:underline">
            ← Retour au projet
        </a>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                ['todo', 'doing', 'done'].forEach(status => {
                    const column = document.getElementById(status);
                    new Sortable(column, {
                        group: 'tasks',
                        animation: 150,
                        onEnd: function (evt) {
                            const taskId = evt.item.dataset.id;
                            const newStatus = evt.to.dataset.status;

                            fetch(`/tasks/${taskId}/update-status`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ status: newStatus })
                            }).then(() => {
                                // Met à jour dynamiquement le texte "Aucune tâche"
                                ['todo', 'doing', 'done'].forEach(status => {
                                    const col = document.getElementById(status);
                                    const message = col.parentElement.querySelector('.no-tasks');

                                    if (col.children.length === 0) {
                                        message.classList.remove('hidden');
                                    } else {
                                        message.classList.add('hidden');
                                    }
                                });
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
