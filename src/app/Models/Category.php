<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * RÉPARATION : Les catégories sont maintenant des labels globaux
     * Plus de relation avec les projets !
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
