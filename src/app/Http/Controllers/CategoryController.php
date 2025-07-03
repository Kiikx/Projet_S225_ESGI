<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project->categories()->create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function destroy(Project $project, Category $category)
    {
        if ($category->project_id !== $project->id) {
            abort(403);
        }

        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }
}
