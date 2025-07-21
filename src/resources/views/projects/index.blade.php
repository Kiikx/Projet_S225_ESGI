<x-app-layout>
    <div class="p-6 space-y-6">
        <!-- En-tête avec bouton d'action -->
        <div class="glass-card rounded-xl p-6 animate-appear">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 accent-gradient rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Gestion des projets</h3>
                        <p class="text-gray-600">{{ $projects->count() }} projet{{ $projects->count() > 1 ? 's' : '' }} au total</p>
                    </div>
                </div>
                <a href="{{ route('projects.create') }}" class="btn-primary flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nouveau projet</span>
                </a>
            </div>
        </div>

        <!-- Grille des projets -->
        @if($projects->count())
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <div class="glass-card rounded-xl p-6 hover:scale-105 transition-all duration-300 animate-slide-in-up group" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="flex items-start justify-between mb-4">
                            <div class="h-10 w-10 accent-gradient rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            
                            <!-- Badge propriétaire -->
                            @if($project->owner_id === Auth::id())
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    Propriétaire
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Membre
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">{{ $project->name }}</h3>
                        
                        @if($project->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $project->description }}</p>
                        @endif
                        
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Créé par {{ $project->owner->name }}</span>
                            </div>
                            
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $project->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>{{ $project->tasks()->count() }} tâche{{ $project->tasks()->count() > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="flex space-x-2 mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('projects.show', $project) }}" class="btn-secondary flex-1 text-center">
                                Voir
                            </a>
                            <a href="{{ route('projects.kanban', $project) }}" class="btn-outline flex-1 text-center">
                                Kanban
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- État vide -->
            <div class="glass-card rounded-xl p-12 text-center animate-fade-in-up">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucun projet</h3>
                <p class="text-gray-600 mb-6">Commencez par créer votre premier projet pour organiser vos tâches</p>
                <a href="{{ route('projects.create') }}" class="btn-primary inline-flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Créer mon premier projet</span>
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
