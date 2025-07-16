<?php

namespace App\Http\Controllers;


use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $projects = Auth::user()->projects;
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id()
        ]);

        $project->members()->attach(Auth::id());

        $project->statuses()->createMany([
            ['name' => 'À faire'],
            ['name' => 'En cours'],
            ['name' => 'Terminé'],
        ]);

        return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
    }

    public function addMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if (!$project->members->contains($request->user_id)) {
            $project->members()->attach($request->user_id);
        }

        return redirect()->back()->with('success', 'Utilisateur ajouté au projet.');
    }
    public function removeMember(Project $project, User $user)
    {
        // Vérifie que le user est bien membre
        if ($project->members->contains($user->id)) {
            $project->members()->detach($user->id);
        }

        return back()->with('success', 'Utilisateur retiré du projet.');
    }


    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $availableUsers = User::whereNotIn('id', $project->members->pluck('id'))->get();

        return view('projects.show', compact('project', 'availableUsers'));
    }

    public function kanban(Project $project)
    {
        $this->authorize('view', $project);

        $project->load('tasks.categories', 'tasks.assignee');

        $tasksByStatus = [
            'todo' => $project->tasks->where('status', 'to_do'),
            'doing' => $project->tasks->where('status', 'doing'),
            'done' => $project->tasks->where('status', 'done'),
        ];

        return view('projects.kanban', compact('project', 'tasksByStatus'));
    }
}
