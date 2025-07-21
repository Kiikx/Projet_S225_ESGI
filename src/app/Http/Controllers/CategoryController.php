<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Gestion des labels globaux
     * Les catégories sont maintenant des labels utilisables dans tous les projets
     */
    
    public function index()
    {
        // Le middleware 'admin' gère déjà les permissions !
        $categories = Category::orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Label ajouté avec succès.');
    }

    public function destroy(Category $category)
    {
        // Vérifier si la catégorie est utilisée
        if ($category->tasks()->count() > 0) {
            return back()->withErrors('Ce label est utilisé par des tâches et ne peut pas être supprimé.');
        }

        $category->delete();
        return back()->with('success', 'Label supprimé.');
    }
}
