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
            ['name' => 'À faire', 'is_protected' => true],
            ['name' => 'En cours', 'is_protected' => true],
            ['name' => 'Fait', 'is_terminal' => true, 'is_protected' => true], // Terminal !
            ['name' => 'Annulé', 'is_protected' => false], // Supprimable
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
        // Le propriétaire ne peut pas se retirer de son propre projet
        if ($project->owner_id === $user->id) {
            return back()->withErrors('Le propriétaire du projet ne peut pas se retirer du projet.');
        }
        
        // Vérifier que l'utilisateur connecté est bien le propriétaire
        if ($project->owner_id !== Auth::id()) {
            return back()->withErrors('Seul le propriétaire peut retirer des membres.');
        }

        // Vérifier que le user est bien membre
        if ($project->members->contains($user->id)) {
            $project->members()->detach($user->id);
            return back()->with('success', 'Utilisateur retiré du projet.');
        }

        return back()->withErrors('Cet utilisateur n\'est pas membre du projet.');
    }


    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $availableUsers = User::whereNotIn('id', $project->members->pluck('id'))
                             ->where('id', '!=', auth()->id())
                             ->get();

        return view('projects.show', compact('project', 'availableUsers'));
    }

    public function kanban(Project $project)
    {
        $this->authorize('view', $project);
        
        return view('projects.kanban', compact('project'));
    }

    public function calendar(Project $project)
    {
        $this->authorize('view', $project);
        
        // Récupérer seulement les tâches qui ont une deadline
        $tasksWithDeadline = $project->tasks()->whereNotNull('deadline')->with(['assignees', 'priority', 'categories'])->get();
        
        return view('projects.calendar', compact('project', 'tasksWithDeadline'));
    }

    public function destroy(Project $project)
    {
        // Only the project owner can delete the project
        if ($project->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Seul le propriétaire du projet peut le supprimer.');
        }
        
        $projectName = $project->name;
        $project->delete();
        
        return redirect()->route('projects.index')->with('success', 'Le projet "' . $projectName . '" a été supprimé avec succès.');
    }
}
