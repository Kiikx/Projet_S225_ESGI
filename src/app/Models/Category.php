<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Les catégories sont des labels globaux, plus de relation avec les projets
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
