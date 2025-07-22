<x-app-layout>

    <div class="p-6 space-y-6">
        <!-- En-tête -->
        <div class="glass-card rounded-xl p-6 animate-appear">
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 accent-gradient rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Catégories globales</h3>
                    <p class="text-gray-600">Gérez les catégories disponibles pour tous les projets</p>
                </div>
            </div>
        </div>

        <!-- Messages de feedback -->
        @if(session('success'))
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

        <!-- Form Errors -->
        <x-form-errors />

        <!-- Formulaire d'ajout -->
        <div class="glass-card rounded-xl p-6 animate-slide-in-left">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-8 w-1 accent-gradient rounded-full"></div>
                <h3 class="text-lg font-semibold text-gray-800">Ajouter une nouvelle catégorie</h3>
            </div>
            
            <form action="{{ route('categories.store') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <x-text-input 
                        type="text" 
                        name="name" 
                        placeholder="Nom de la catégorie (ex: Marketing, Développement, Design...)" 
                        class="w-full"
                        required
                        maxlength="255"
                    />
                </div>
                <button type="submit" class="btn-primary flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Ajouter</span>
                </button>
            </form>
        </div>

        <!-- Liste des catégories -->
        <div class="glass-card rounded-xl p-6 animate-slide-in-right">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-1 accent-gradient rounded-full"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Catégories existantes</h3>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                    {{ $categories->count() }} catégorie{{ $categories->count() > 1 ? 's' : '' }}
                </span>
            </div>
            
            @if($categories->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        <div class="bg-white/50 border border-gray-100 rounded-lg px-4 py-3 hover:shadow-md transition-all duration-200 animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 accent-gradient rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-800">{{ $category->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $category->tasks->count() }} tâche{{ $category->tasks->count() > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                
                                @if($category->tasks->count() === 0)
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" 
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                        Utilisée
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Aucune catégorie</h4>
                    <p class="text-gray-600 mb-6">Commencez par ajouter votre première catégorie pour organiser les tâches</p>
                </div>
            @endif
        </div>

        <!-- Aide -->
        <div class="glass-card rounded-xl p-6 animate-fade-in-up">
            <div class="flex items-start space-x-3">
                <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">💡 Conseils d'utilisation</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Ces catégories sont disponibles dans tous les projets</li>
                        <li>• Vous ne pouvez supprimer que les catégories non utilisées</li>
                        <li>• Créez des catégories claires comme "Développement", "Design", "Marketing"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
