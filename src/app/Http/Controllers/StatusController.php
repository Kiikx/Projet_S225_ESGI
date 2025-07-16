<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class StatusController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);


        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project->statuses()->create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Colonne ajoutée avec succès.');
    }

    public function destroy(Project $project, \App\Models\Status $status)
{
    $this->authorize('update', $project);

    if (in_array($status->name, ['À faire', 'En cours', 'Terminé'])) {
        return back()->with('error', 'Impossible de supprimer une colonne par défaut.');
    }


    if ($status->tasks()->exists()) {
        return back()->with('error', 'Impossible de supprimer une colonne contenant des tâches.');
    }

    $status->delete();

    return back()->with('success', 'Colonne supprimée avec succès.');
}
}
