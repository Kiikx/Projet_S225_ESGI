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
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $todoStatus = $project->statuses()->where('name', 'À faire')->first();
        $task = $project->tasks()->create([
            ...$validated,
            'creator_id' => Auth::id(),
            'status_id' => $todoStatus->id,
        ]);

        $task->categories()->sync($request->categories ?? []);


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
            'assigned_to_id' => 'nullable|exists:users,id',
            'status_id' => 'sometimes|nullable|exists:statuses,id',
        ]);

        $task->update($validated);

        $task->categories()->sync($request->categories ?? []);

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

        $task->status_id = $validated['status_id'];
        $task->save();

        return response()->json(['success' => true]);
    }
}
