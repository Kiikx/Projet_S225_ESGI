<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
        <!-- Informations du projet centrées -->
        <div class="bg-gradient-to-br from-white to-purple-50/30 rounded-xl shadow-lg border border-purple-100/50 p-6 text-center">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-700 to-blue-600 bg-clip-text text-transparent mb-3">{{ $project->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $project->description ?? 'Aucune description.' }}</p>
            
            <div class="flex items-center justify-center gap-8 text-sm text-gray-600">
                <div class="flex items-center gap-2 bg-purple-50 px-3 py-1 rounded-full">
                    <span class="text-purple-500">👤</span>
                    <span>Propriétaire : <strong class="text-purple-700">{{ $project->owner->name ?? 'Inconnu' }}</strong></span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 px-3 py-1 rounded-full">
                    <span class="text-blue-500">👥</span>
                    <span><strong class="text-blue-700">{{ $project->members->count() }}</strong> membre(s)</span>
                </div>
                <div class="flex items-center gap-2 bg-indigo-50 px-3 py-1 rounded-full">
                    <span class="text-indigo-500">📅</span>
                    <span>Créé le <strong class="text-indigo-700">{{ $project->created_at->format('d/m/Y') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Boutons d'action du projet -->
        <div class="space-y-4">
            <!-- HERO KANBAN BUTTON - GRANDE BANNIERE -->
            <div class="text-center">
                <a href="{{ route('projects.kanban', $project) }}"
                   class="group relative inline-block overflow-hidden bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-2xl shadow-xl transform transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 to-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></div>
                    <div class="relative flex items-center justify-center gap-3">
                        <span class="text-2xl">📊</span>
                        <div class="text-center">
                            <div class="text-xl font-bold leading-tight mb-1">Accéder au Tableau Kanban</div>
                            <div class="text-xs text-blue-100 font-normal">Gérez vos tâches visuellement</div>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Actions secondaires -->
            <div class="flex justify-center gap-4">
                <a href="{{ route('projects.tasks.index', $project) }}"
                   class="text-gray-600 hover:text-blue-600 text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                    📋 <span>Voir toutes les tâches</span>
                </a>
                
                <button onclick="toggleMembersModal()"
                        class="text-gray-600 hover:text-purple-600 text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                    👥 <span>Gérer les membres ({{ $project->members->count() }})</span>
                </button>
            </div>
        </div>

        <!-- Modal de gestion des membres -->
        <div id="membersModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center cursor-pointer">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto cursor-default">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Membres du projet</h3>
                        <button onclick="toggleMembersModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                            ×
                        </button>
                    </div>
                    
                    @if ($project->members->count())
                        <div class="space-y-3 mb-6">
                            @foreach ($project->members as $member)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                            @if($member->id === $project->owner_id)
                                                <span class="text-xs text-yellow-600">👑 Propriétaire</span>
                                            @else
                                                <span class="text-xs text-gray-500">Membre</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($member->id !== $project->owner_id && $project->owner_id === auth()->id())
                                        <form action="{{ route('projects.removeMember', [$project, $member]) }}" method="POST"
                                            onsubmit="return confirm('Retirer {{ $member->name }} du projet ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50" type="submit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <p>Aucun membre pour l'instant</p>
                        </div>
                    @endif

                    @if($project->owner_id === auth()->id())
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Ajouter un nouveau membre</h4>
                            <form action="{{ route('projects.addMember', $project) }}" method="POST">
                                @csrf
                                <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">-- Sélectionner un utilisateur --</option>
                                    @foreach ($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    👥 Ajouter au projet
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques en ligne -->
        @php
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->filter(fn($t) => $t->completed_at)->count();
            $highPriorityTasks = $project->tasks->filter(fn($t) => $t->priority && $t->priority->level == 3)->count();
            $overdueTasks = $project->tasks->filter(fn($t) => $t->deadline && $t->deadline->isPast() && !$t->completed_at)->count();
        @endphp
        
        <div class="flex gap-4">
            <!-- Stat 1 : Total tâches -->
            <div class="flex-1 bg-white rounded-lg shadow p-3 text-center">
                <div class="text-3xl mb-1">📋</div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $totalTasks }}</div>
                <div class="text-xs text-gray-600">Total tâches</div>
            </div>

            <!-- Stat 2 : Complétées -->
            <div class="flex-1 bg-white rounded-lg shadow p-3 text-center">
                <div class="text-3xl mb-1">✅</div>
                <div class="text-2xl font-bold text-green-600 mb-1">{{ $completedTasks }}</div>
                <div class="text-xs text-gray-600">Complétées</div>
            </div>

            <!-- Stat 3 : Priorité haute -->
            <div class="flex-1 bg-white rounded-lg shadow p-3 text-center">
                <div class="text-3xl mb-1">🔥</div>
                <div class="text-2xl font-bold text-red-600 mb-1">{{ $highPriorityTasks }}</div>
                <div class="text-xs text-gray-600">Priorité haute</div>
            </div>

            <!-- Stat 4 : En retard -->
            <div class="flex-1 bg-white rounded-lg shadow p-3 text-center">
                <div class="text-3xl mb-1">⚠️</div>
                <div class="text-2xl font-bold text-orange-600 mb-1">{{ $overdueTasks }}</div>
                <div class="text-xs text-gray-600">En retard</div>
            </div>
        </div>

        <!-- Section des tâches en pleine largeur -->
        @if ($project->tasks->count())
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold text-gray-700">Tâches récentes</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($project->tasks->take(10) as $task)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                            @if($task->priority)
                                                <span class="px-2 py-1 rounded text-xs font-medium
                                                    @if($task->priority->level == 1) bg-green-100 text-green-700
                                                    @elseif($task->priority->level == 2) bg-orange-100 text-orange-700  
                                                    @elseif($task->priority->level == 3) bg-red-100 text-red-700
                                                    @else bg-gray-100 text-gray-700 @endif">
                                                    {{ $task->priority->label }}
                                                </span>
                                            @endif
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                @if($task->status?->name === 'Fait') bg-green-100 text-green-700
                                                @elseif($task->status?->name === 'En cours') bg-yellow-100 text-yellow-700  
                                                @elseif($task->status?->name === 'Annulé') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ $task->status?->name ?? 'À faire' }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            @if($task->assignees->count())
                                                <div class="flex items-center gap-1">
                                                    <span>👤</span>
                                                    @foreach($task->assignees->take(2) as $assignee)
                                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $assignee->name }}</span>
                                                    @endforeach
                                                    @if($task->assignees->count() > 2)
                                                        <span class="text-xs text-gray-500">+{{ $task->assignees->count() - 2 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if($task->deadline)
                                                <div class="flex items-center gap-1
                                                    @if($task->deadline->isPast() && !$task->completed_at) text-red-600 @endif">
                                                    <span>📅</span>
                                                    <span>{{ $task->deadline->format('d/m/Y') }}</span>
                                                    @if($task->deadline->isPast() && !$task->completed_at)
                                                        <span class="text-red-500 font-bold text-xs">(en retard)</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('tasks.edit', $task) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Modifier
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($project->tasks->count() > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('projects.tasks.index', $project) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir toutes les {{ $project->tasks->count() }} tâches →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-gray-400 text-6xl mb-4">📝</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune tâche</h3>
                <p class="text-gray-600 mb-4">Ce projet n'a pas encore de tâches.</p>
                <a href="{{ route('projects.tasks.create', $project) }}" 
                   class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    ➕ Créer la première tâche
                </a>
            </div>
        @endif
        
        <!-- Bouton supprimer projet tout en bas - Zone dangereuse -->
        @if($project->owner_id === auth()->id())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <h3 class="text-red-800 font-semibold mb-2">Zone dangereuse</h3>
                <p class="text-red-600 text-sm mb-4">La suppression du projet est irréversible et supprimera toutes les tâches associées.</p>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')" 
                      class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                        🗑️ Supprimer définitivement le projet
                    </button>
                </form>
            </div>
        @endif
    </div>
    
    <!-- BOUTON FIXE NOUVELLE TACHE - Suit le scroll -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('projects.tasks.create', $project) }}"
           class="group flex items-center bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold shadow-2xl transform transition-all duration-300 hover:scale-110 hover:shadow-3xl
                  w-16 h-16 md:w-auto md:h-auto md:py-4 md:px-6 rounded-full justify-center md:justify-start">
            <span class="text-2xl md:mr-3 group-hover:rotate-90 transition-transform duration-300">➕</span>
            <div class="hidden md:block">
                <div class="font-bold leading-tight">Nouvelle tâche</div>
                <div class="text-xs text-emerald-100 font-normal">Création rapide</div>
            </div>
        </a>
    </div>
    
    <script>
        function toggleMembersModal() {
            const modal = document.getElementById('membersModal');
            modal.classList.toggle('hidden');
        }
        
        // Fermer le modal en cliquant sur le fond noir
        document.getElementById('membersModal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleMembersModal();
            }
        });
        
        // Fermer le modal avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('membersModal');
                if (!modal.classList.contains('hidden')) {
                    toggleMembersModal();
                }
            }
        });
    </script>
</x-app-layout>
