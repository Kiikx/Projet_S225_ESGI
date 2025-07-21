<x-app-layout>

    <div class="p-6 space-y-6">
        <!-- En-tête stylé du Kanban -->
        <div class="glass-card rounded-xl p-6 animate-appear">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 accent-gradient rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-800">{{ $project->name }}</h1>
                        <p class="text-gray-600">Tableau Kanban • {{ $project->tasks->count() }} tâche{{ $project->tasks->count() > 1 ? 's' : '' }} au total</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('projects.show', $project) }}" class="btn-outline flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Vue détail</span>
                    </a>
                    <a href="{{ route('projects.tasks.create', $project) }}" class="btn-primary flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Nouvelle tâche</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Messages de feedback -->
        @if (session('success'))
            <div class="glass-card rounded-xl p-4 animate-slide-in-up">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Formulaire d'ajout de colonne -->
        <div class="glass-card rounded-xl p-6 animate-appear">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-1 accent-gradient rounded-full"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Gestion des colonnes</h3>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                    {{ $project->statuses->count() }} colonne{{ $project->statuses->count() > 1 ? 's' : '' }}
                </span>
            </div>

            <form action="{{ route('projects.statuses.store', $project) }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <x-text-input 
                        type="text" 
                        name="name" 
                        placeholder="Nom de la nouvelle colonne (ex: En test, Validé...)" 
                        class="w-full"
                        required
                    />
                </div>
                <button type="submit" class="btn-secondary flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Ajouter colonne</span>
                </button>
            </form>
        </div>

        <!-- Board Kanban -->
        <div class="kanban-board overflow-x-auto">
            <div class="flex gap-6 pb-4 min-w-max" style="min-width: {{ $project->statuses->count() * 380 }}px;">
            @foreach ($project->statuses as $status)
                @php
                    $tasksInStatus = $project->tasks->where('status_id', $status->id);
                    // Définir les couleurs selon le statut
                    $columnClass = 'glass-card';
                    $bulletClass = 'accent-gradient';
                    $opacity = 'opacity-100';
                    
                    if (str_contains(strtolower($status->name), 'faire') || str_contains(strtolower($status->name), 'todo')) {
                        $columnClass = 'glass-card bg-red-50/30 border-red-100/50';
                        $bulletClass = 'bg-red-400';
                    } elseif (str_contains(strtolower($status->name), 'cours') || str_contains(strtolower($status->name), 'progress') || str_contains(strtolower($status->name), 'en cours')) {
                        $columnClass = 'glass-card bg-orange-50/30 border-orange-100/50';
                        $bulletClass = 'bg-orange-400';
                    } elseif (str_contains(strtolower($status->name), 'fait') || str_contains(strtolower($status->name), 'done') || str_contains(strtolower($status->name), 'terminé') || str_contains(strtolower($status->name), 'fini')) {
                        $columnClass = 'glass-card bg-green-50/30 border-green-100/50';
                        $bulletClass = 'bg-green-400';
                    } elseif (str_contains(strtolower($status->name), 'annulé') || str_contains(strtolower($status->name), 'cancelled') || str_contains(strtolower($status->name), 'abandon')) {
                        $columnClass = 'glass-card bg-gray-50/30 border-gray-100/50';
                        $bulletClass = 'bg-gray-400';
                        $opacity = 'opacity-70';
                    }
                @endphp
                <div class="kanban-column {{ $columnClass }} {{ $opacity }} rounded-xl p-4 animate-slide-in-up flex-shrink-0" style="width: 360px; animation-delay: {{ $loop->index * 0.1 }}s">
                    <!-- En-tête de colonne -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-3 w-3 {{ $bulletClass }} rounded-full flex-shrink-0"></div>
                            <h3 class="font-semibold text-gray-800 truncate">{{ $status->name }}</h3>
                        </div>
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full flex-shrink-0">
                            {{ $tasksInStatus->count() }}
                        </span>
                    </div>

                    <!-- Zone de drop -->
                    <div class="task-column min-h-[400px]" data-status-id="{{ $status->id }}"
                        ondragover="event.preventDefault();" ondrop="handleDrop(event, {{ $status->id }})">
                        
                        @forelse($tasksInStatus as $task)
                            <div class="task-card bg-white/80 backdrop-blur-sm border border-white/20 shadow-sm hover:shadow-md transition-all duration-200 p-4 mb-3 rounded-xl cursor-move group" 
                                 draggable="true" data-id="{{ $task->id }}" ondragstart="handleDragStart(event)">
                                
                                <!-- En-tête de tâche -->
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-medium text-gray-800 text-sm leading-tight flex-1 pr-2">{{ $task->title }}</h4>
                                    @if($task->priority)
                                        <span class="flex-shrink-0 px-2 py-1 rounded-lg text-xs font-medium
                                            @if($task->priority->level == 1) bg-green-100 text-green-700
                                            @elseif($task->priority->level == 2) bg-orange-100 text-orange-700  
                                            @elseif($task->priority->level == 3) bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ $task->priority->label }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Description si présente -->
                                @if($task->description)
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>
                                @endif

                                <!-- Date limite -->
                                @if($task->deadline)
                                    <div class="flex items-center space-x-2 mb-3">
                                        <svg class="w-3 h-3 @if($task->deadline->isPast() && !$task->completed_at) text-red-500 @else text-gray-400 @endif" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs @if($task->deadline->isPast() && !$task->completed_at) text-red-600 font-medium @else text-gray-500 @endif">
                                            {{ $task->deadline->format('d/m/Y') }}
                                            @if($task->deadline->isPast() && !$task->completed_at)
                                                <span class="text-red-500 font-bold ml-1">(Retard)</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif

                                <!-- Assignés -->
                                @if($task->assignees->count())
                                    <div class="flex items-center space-x-2 mb-3">
                                        <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($task->assignees as $assignee)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs font-medium">{{ $assignee->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Catégories -->
                                @if($task->categories->count())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($task->categories as $category)
                                            <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <!-- Indicateur de drag -->
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 mt-2 pt-2 border-t border-gray-100">
                                    <div class="flex items-center justify-center text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-column bg-white/30 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                                <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm font-medium">Aucune tâche</p>
                                <p class="text-gray-400 text-xs mt-1">Glissez une tâche ici</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Bouton de suppression de colonne -->
                    @if (!$status->is_protected)
                        <div class="delete-column-container mt-4 pt-3 border-t border-gray-200" style="{{ $tasksInStatus->isNotEmpty() ? 'display: none;' : '' }}">
                            <form action="{{ route('projects.statuses.destroy', [$project, $status]) }}" method="POST"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette colonne ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-red-500 hover:bg-red-50 text-sm py-2 px-3 rounded-lg transition-colors flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Supprimer colonne</span>
                                </button>
                            </form>
                        </div>
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
                    <div class="empty-column bg-white/30 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Aucune tâche</p>
                        <p class="text-gray-400 text-xs mt-1">Glissez une tâche ici</p>
                    </div>
                `;
            }
            
            // Gérer la visibilité des boutons de suppression
            updateDeleteButtons();

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
        
        // Fonction pour mettre à jour la visibilité des boutons de suppression
        function updateDeleteButtons() {
            const columns = document.querySelectorAll('.task-column');
            columns.forEach(column => {
                const deleteButton = column.parentElement.querySelector('.delete-column-container');
                if (deleteButton) {
                    const hasTasks = column.querySelector('.task-card') !== null;
                    deleteButton.style.display = hasTasks ? 'none' : 'block';
                }
            });
        }
    </script>
</x-app-layout>
