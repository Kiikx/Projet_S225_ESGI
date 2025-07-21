<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Labels</h1>
            <p class="text-gray-600 mb-6">Gérez les labels globaux utilisables dans tous les projets (Marketing, Développement, etc.)</p>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Formulaire d'ajout -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-4">Ajouter un nouveau label</h3>
                
                <form action="{{ route('categories.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Nom du label (ex: Design, Tests, etc.)" 
                        class="flex-1 border rounded px-3 py-2"
                        required
                        maxlength="255"
                    >
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition">
                        ➕ Ajouter
                    </button>
                </form>
            </div>

            <!-- Liste des labels -->
            <div class="space-y-3">
                <h3 class="text-lg font-semibold">Labels existants ({{ $categories->count() }})</h3>
                
                @if($categories->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categories as $category)
                            <div class="flex items-center justify-between bg-gray-50 border rounded-lg px-4 py-3">
                                <div class="flex items-center">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-medium">
                                        {{ $category->name }}
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">
                                        ({{ $category->tasks->count() }} tâches)
                                    </span>
                                </div>
                                
                                @if($category->tasks->count() === 0)
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" 
                                          onsubmit="return confirm('Supprimer ce label ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                            ✕
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">En usage</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        Aucun label personnalisé. Commencez par en ajouter un !
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-6 border-t text-sm text-gray-500">
                💡 <strong>Astuce :</strong> Ces labels sont utilisables dans tous les projets. Vous ne pouvez supprimer que les labels non utilisés.
            </div>
        </div>
    </div>
</x-app-layout>
