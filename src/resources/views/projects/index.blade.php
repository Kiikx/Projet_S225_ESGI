<x-app-layout>
    <x-slot name="header">Mes projets</x-slot>

    <a href="{{ route('projects.create') }}" class="btn btn-primary">Nouveau projet</a>

    <ul>
        @foreach ($projects as $project)
            <li>
                <a href="{{ route('projects.show', $project) }}">
                    {{ $project->name }} {{$project->id}} {{$project->owner->name}} {{$project->owner->id}}
                </a>
            </li>
        @endforeach
    </ul>
</x-app-layout>
