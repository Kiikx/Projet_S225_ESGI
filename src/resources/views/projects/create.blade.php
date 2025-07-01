<x-app-layout>
    <x-slot name="header">Créer un projet</x-slot>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div>
            <label>Nom du projet</label>
            <input type="text" name="name" required>
        </div>

        <div>
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>

        <button type="submit">Créer</button>
    </form>
</x-app-layout>
