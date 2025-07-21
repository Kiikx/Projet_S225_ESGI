<x-app-layout>
    <x-form-container 
        title="Nouvelle tâche"
        subtitle="Créer une tâche pour le projet {{ $project->name }}">
        
        <!-- Form Errors -->
        <x-form-errors />
        
        <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="space-y-6">
            @csrf

            <x-form-group>
                <x-input-label for="title" :required="true">Titre de la tâche</x-input-label>
                <x-text-input 
                    id="title" 
                    name="title" 
                    type="text" 
                    placeholder="Ex: Finaliser la maquette de la page d'accueil"
                    required />
            </x-form-group>

            <x-form-group>
                <x-input-label for="description">Description</x-input-label>
                <x-textarea-input 
                    id="description" 
                    name="description" 
                    placeholder="Décrivez les détails de cette tâche..."
                    rows="4" />
            </x-form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-group>
                    <x-input-label for="priority_id">Priorité</x-input-label>
                    <x-select-input id="priority_id" name="priority_id">
                        <option value="">Sélectionner une priorité</option>
                        @foreach(App\Models\Priority::orderByLevel()->get() as $priority)
                            <option value="{{ $priority->id }}">
                                {{ $priority->label }}
                            </option>
                        @endforeach
                    </x-select-input>
                </x-form-group>

                <x-form-group>
                    <x-input-label for="deadline">Date limite</x-input-label>
                    <x-text-input 
                        id="deadline" 
                        name="deadline" 
                        type="date" />
                    <p class="text-xs text-neutral-500 mt-1">Optionnel - laissez vide si aucune date limite</p>
                </x-form-group>
            </div>

            <x-form-group>
                <x-input-label for="categories">Catégories</x-input-label>
                <x-multiselect 
                    name="categories"
                    placeholder="Choisir des catégories..."
                    searchPlaceholder="Rechercher une catégorie..."
                    :options="collect(App\Models\Category::all())->map(fn($cat) => ['value' => $cat->id, 'label' => $cat->name])->toArray()"
                    :selected="[]"
                />
                <p class="text-xs text-neutral-500 mt-1">Tapez pour rechercher et cliquez pour sélectionner</p>
            </x-form-group>

            <x-form-group>
                <x-input-label for="assignees">Assigner à</x-input-label>
                <x-multiselect 
                    name="assignees"
                    placeholder="Choisir des membres..."
                    searchPlaceholder="Rechercher un membre..."
                    :options="$project->members->map(fn($user) => ['value' => $user->id, 'label' => $user->name])->toArray()"
                    :selected="[]"
                />
                <p class="text-xs text-neutral-500 mt-1">Sélectionnez les membres qui travailleront sur cette tâche</p>
            </x-form-group>

            <div class="flex justify-center pt-6 border-t border-neutral-200">
                <x-primary-button>
                    <span class="text-lg mr-2">✨</span>
                    Créer la tâche
                </x-primary-button>
            </div>
        </form>
    </x-form-container>
</x-app-layout>
