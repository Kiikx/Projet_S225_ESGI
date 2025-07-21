<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project): mixed
    {
        return view('tasks.index', compact('project'));
    }

    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority_id' => 'nullable|exists:priorities,id',
            'deadline' => 'nullable|date|after_or_equal:today',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $todoStatus = $project->statuses()->where('name', 'À faire')->first();
        // On exclut assignees du fillable (relation many-to-many)
        $taskData = collect($validated)->except('assignees')->toArray();
        $task = $project->tasks()->create([
            ...$taskData,
            'creator_id' => Auth::id(),
            'status_id' => $todoStatus->id,
        ]);

        $task->categories()->sync($request->categories ?? []);
        $task->assignees()->sync($request->assignees ?? []);


        return redirect()->route('projects.show', $project)->with('success', 'Tâche créée');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'priority_id' => 'sometimes|nullable|exists:priorities,id',
            'deadline' => 'sometimes|nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
            'status_id' => 'sometimes|nullable|exists:statuses,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // On exclut assignees et categories du fillable (relations many-to-many)
        $taskData = collect($validated)->except(['assignees', 'categories'])->toArray();
        $task->update($taskData);

        // Ne synchroniser les relations que si elles sont présentes dans la requête
        if ($request->has('categories')) {
            $task->categories()->sync($request->categories ?? []);
        }
        
        if ($request->has('assignees')) {
            $task->assignees()->sync($request->assignees ?? []);
        }

        return redirect()->route('projects.show', $task->project)->with('success', 'Tâche mise à jour');
    }

    public function destroy(Task $task)
    {
        $project = $task->project;
        $task->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Tâche supprimée');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task->project);

        $validated = $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        $oldStatusId = $task->status_id;
        $newStatus = \App\Models\Status::find($validated['status_id']);
        
        $task->status_id = $validated['status_id'];
        
        // Auto-complétion : marquer comme complété si passage vers statut terminal
        if ($newStatus && $newStatus->is_terminal && !$task->completed_at) {
            $task->completed_at = now();
        }
        // Si on revient d'un statut terminal vers un non-terminal, enlever la complétion
        elseif ($task->completed_at && $newStatus && !$newStatus->is_terminal) {
            $task->completed_at = null;
        }
        
        $task->save();

        return response()->json(['success' => true]);
    }
}
