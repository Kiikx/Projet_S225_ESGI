<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
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
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $task = $project->tasks()->create([
            ...$validated,
            'creator_id' => Auth::id(),
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
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:todo,in_progress,done',
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
        $request->validate([
            'status' => 'required|in:todo,doing,done',
        ]);

        $task->status = $request->status;
        $task->save();

        return response()->json(['success' => true]);
    }

}
