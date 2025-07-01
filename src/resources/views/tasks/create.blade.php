<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Créer une tâche pour le projet : {{ $project->title }}</h2>

        <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block font-semibold">Titre</label>
                <input type="text" name="title" id="title" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label for="description" class="block font-semibold">Description</label>
                <textarea name="description" id="description" class="w-full border rounded p-2"></textarea>
            </div>

            <div>
                <label for="priority_id" class="block font-semibold">Priorité</label>
                <select name="priority_id" id="priority_id" class="w-full border rounded p-2">
                    @foreach(App\Models\Priority::all() as $priority)
                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="category_id" class="block font-semibold">Catégorie</label>
                <select name="category_id" id="category_id" class="w-full border rounded p-2">
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assigned_to_id" class="block font-semibold">Assigner à</label>
                <select name="assigned_to_id" id="assigned_to_id" class="w-full border rounded p-2">
                    <option value="">-- Aucun --</option>
                    @foreach(App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Créer la tâche
            </button>
        </form>
    </div>
</x-app-layout>