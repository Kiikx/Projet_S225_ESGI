<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Détails du projet -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $project->title }}</h1>
            <p class="text-gray-600">{{ $project->description ?? 'Aucune description.' }}</p>

            <div class="mt-4 text-sm text-gray-500">
                Créé par : {{ $project->owner->name ?? 'Inconnu' }}
            </div>

            <!-- BOUTONS : Voir toutes les tâches + Ajouter une tâche -->
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('projects.tasks.index', $project) }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    📋 Voir toutes les tâches
                </a>

                <a href="{{ route('projects.tasks.create', $project) }}"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    ➕ Ajouter une tâche
                </a>
            </div>
        </div>

        <div class="mt-6 bg-white shadow rounded p-4">
            <h3 class="text-lg font-semibold mb-2">Ajouter un membre au projet</h3>

            @if($project->members->count())
                <div class="mt-6 bg-white shadow rounded p-4">
                    <h3 class="text-lg font-semibold mb-2">Membres du projet</h3>

                    <ul class="space-y-2">
                        @foreach($project->members as $member)
                            <li class="flex justify-between items-center border-b pb-2">
                                <span>{{ $member->name }}</span>

                                <form action="{{ route('projects.removeMember', [$project, $member]) }}" method="POST"
                                    onsubmit="return confirm('Retirer ce membre ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline text-sm" type="submit">
                                        Retirer
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('projects.addMember', $project) }}" method="POST" class="flex gap-4">
                @csrf
                <select name="user_id" class="border rounded px-3 py-2 w-full">
                    <option value="">-- Choisir un utilisateur --</option>
                    @foreach(App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                        @if(!$project->members->contains($user))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Ajouter
                </button>
            </form>
        </div>

        <!-- Section optionnelle des tâches récentes -->
        @if($project->tasks->count())
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Tâches récentes</h2>

                <ul class="space-y-2">
                    @foreach($project->tasks->take(5) as $task)
                        <li class="flex justify-between items-center bg-gray-50 border rounded px-4 py-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $task->title }}</p>
                                <p class="text-sm text-gray-500">{{ $task->status }}</p>
                            </div>

                            <a href="{{ route('tasks.edit', $task) }}" class="text-blue-500 hover:underline text-sm">
                                Modifier
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-gray-500 text-center">
                Ce projet n’a pas encore de tâches.
            </div>
        @endif
    </div>
</x-app-layout>