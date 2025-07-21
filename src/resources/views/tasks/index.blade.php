<x-app-layout>
    <div class="p-6 space-y-6">
        <!-- En-tête stylé -->
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
                        <p class="text-gray-600">Gestion des tâches • {{ $project->tasks->count() }} tâche{{ $project->tasks->count() > 1 ? 's' : '' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('projects.kanban', $project) }}" class="btn-outline flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Vue Kanban</span>
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

        @if($project->tasks->count())
            <!-- Tableau moderne -->
            <div class="glass-card rounded-xl overflow-hidden animate-appear">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tâche</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priorité</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assignée à</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catégories</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($project->tasks as $task)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200 animate-slide-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                    <!-- Tâche -->
                                    <td class="px-6 py-4">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                            <p class="text-xs text-gray-400 mt-1">Par {{ $task->creator?->name ?? 'Inconnu' }}</p>
                                        </div>
                                    </td>
                                    
                                    <!-- Priorité -->
                                    <td class="px-6 py-4">
                                        @if($task->priority)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                @if($task->priority->level == 1) bg-green-100 text-green-800
                                                @elseif($task->priority->level == 2) bg-orange-100 text-orange-800  
                                                @elseif($task->priority->level == 3) bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $task->priority->label }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Statut -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($task->status?->name === 'Fait') bg-green-100 text-green-800
                                            @elseif($task->status?->name === 'En cours') bg-orange-100 text-orange-800  
                                            @elseif($task->status?->name === 'Annulé') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $task->status?->name ?? 'À faire' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Deadline -->
                                    <td class="px-6 py-4">
                                        @if($task->deadline)
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 @if($task->deadline->isPast() && !$task->completed_at) text-red-500 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm @if($task->deadline->isPast() && !$task->completed_at) text-red-600 font-medium @else text-gray-900 @endif">
                                                    {{ $task->deadline->format('d/m/Y') }}
                                                    @if($task->deadline->isPast() && !$task->completed_at)
                                                        <span class="text-red-500 font-bold ml-1">(Retard)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Assignées -->
                                    <td class="px-6 py-4">
                                        @if($task->assignees->count())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($task->assignees as $assignee)
                                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        {{ $assignee->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">Non assignée</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Catégories -->
                                    <td class="px-6 py-4">
                                        @if($task->categories->count())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($task->categories as $category)
                                                    <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Modifier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            
                                            @if($task->status?->name !== 'Fait')
                                                @php
                                                    $doneStatus = $project->statuses->where('name', 'Fait')->first();
                                                @endphp
                                                @if($doneStatus)
                                                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status_id" value="{{ $doneStatus->id }}">
                                                        <button type="submit" class="text-green-600 hover:text-green-800 transition-colors" title="Marquer comme terminée">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette tâche ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Supprimer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- État vide -->
            <div class="glass-card rounded-xl p-12 text-center animate-fade-in-up">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune tâche</h3>
                <p class="text-gray-600 mb-6">Ce projet ne contient encore aucune tâche</p>
                <a href="{{ route('projects.tasks.create', $project) }}" class="btn-primary inline-flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Créer la première tâche</span>
                </a>
            </div>
        @endif
    </div>
</x-app-layout>