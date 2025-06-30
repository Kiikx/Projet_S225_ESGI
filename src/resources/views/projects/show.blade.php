<x-app-layout>
    <x-slot name="header">{{ $project->name }}</x-slot>

    <p>{{ $project->description }}</p>

    <p>Membres : {{ $project->members->pluck('name')->join(', ') }}</p>

    <a href="{{ route('projects.index') }}">← Retour</a>
</x-app-layout>
