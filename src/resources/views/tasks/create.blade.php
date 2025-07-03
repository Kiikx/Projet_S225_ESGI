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

            <div class="mb-4">
                <label for="categories" class="block text-gray-700">Catégories</label>
                <select name="categories[]" id="categories" multiple
                    class="w-full border border-gray-300 rounded px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (isset($task) && $task->categories->contains($category->id)) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
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