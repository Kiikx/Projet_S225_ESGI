<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class CalendarController extends Controller
{
    use AuthorizesRequests;

    /**
     * Afficher le calendrier d'un projet
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        
        // Récupérer toutes les tâches avec deadline du projet
        $tasksWithDeadline = $project->tasks()
            ->whereNotNull('deadline')
            ->with(['assignees', 'priority', 'categories', 'status'])
            ->orderBy('deadline')
            ->get();
        
        // Statistiques du calendrier
        $stats = [
            'total_tasks' => $tasksWithDeadline->count(),
            'overdue_tasks' => $tasksWithDeadline->filter(function($task) {
                return $task->deadline->isPast() && !$task->completed_at;
            })->count(),
            'this_week' => $tasksWithDeadline->filter(function($task) {
                return $task->deadline->isCurrentWeek();
            })->count(),
            'next_week' => $tasksWithDeadline->filter(function($task) {
                return $task->deadline->between(
                    Carbon::now()->addWeek()->startOfWeek(),
                    Carbon::now()->addWeek()->endOfWeek()
                );
            })->count(),
        ];
        
        return view('projects.calendar', compact('project', 'tasksWithDeadline', 'stats'));
    }

    /**
     * API pour récupérer les tâches d'une période
     */
    public function getTasks(Request $request, Project $project)
    {
        $this->authorize('view', $project);
        
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));
        
        $tasks = $project->tasks()
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [$start, $end])
            ->with(['assignees', 'priority', 'categories', 'status'])
            ->get();
            
        return response()->json($tasks);
    }

    /**
     * Mettre à jour rapidement une tâche depuis le calendrier
     */
    public function updateTask(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $request->validate([
            'deadline' => 'required|date',
        ]);
        
        $task->update([
            'deadline' => $request->deadline,
        ]);
        
        return response()->json([
            'success' => true,
            'task' => $task->load(['assignees', 'priority', 'categories'])
        ]);
    }
}
