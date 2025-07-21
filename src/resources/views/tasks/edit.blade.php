<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Modifier la tâche : {{ $task->title }}</h2>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block font-semibold">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
                    class="w-full border rounded p-2" required>
            </div>

            <div>
                <label for="description" class="block font-semibold">Description</label>
                <textarea name="description" id="description"
                    class="w-full border rounded p-2">{{ old('description', $task->description) }}</textarea>
            </div>

            <div>
                <label for="priority_id" class="block font-semibold">Priorité</label>
                <select name="priority_id" id="priority_id" class="w-full border rounded p-2">
                    @foreach(App\Models\Priority::all() as $priority)
                        <option value="{{ $priority->id }}" {{ $task->priority_id == $priority->id ? 'selected' : '' }}>
                            {{ $priority->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="categories" class="block text-gray-700">Catégories</label>
                <select name="categories[]" id="categories" multiple
                    class="w-full border border-gray-300 rounded px-3 py-2">
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ (isset($task) && $task->categories->contains($category->id)) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assignees" class="block font-semibold">Assigner à (membres du projet)</label>
                <select name="assignees[]" id="assignees" multiple class="w-full border rounded p-2">
                    @foreach($task->project->members as $user)
                        <option value="{{ $user->id }}" 
                            {{ $task->assignees->contains($user->id) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-500">Maintenez Ctrl pour sélectionner plusieurs personnes</small>
            </div>


            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Mettre à jour
            </button>
        </form>
    </div>
</x-app-layout>