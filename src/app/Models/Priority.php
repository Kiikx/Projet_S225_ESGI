<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = [
        'label',
        'level',
    ];

    /**
     * Relation : une priorité a plusieurs tâches
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Scope pour trier par niveau (du plus important au moins important)
     */
    public function scopeOrderByLevel($query)
    {
        return $query->orderBy('level', 'desc');
    }

    /**
     * Méthode pour obtenir la couleur selon le niveau de priorité
     */
    public function getColorAttribute()
    {
        return match($this->level) {
            1 => 'success', // Vert pour basse
            2 => 'warning', // Orange pour moyenne
            3 => 'danger',  // Rouge pour élevée
            default => 'secondary'
        };
    }

    /**
     * Méthode pour obtenir l'icône selon le niveau de priorité
     */
    public function getIconAttribute()
    {
        return match($this->level) {
            1 => 'arrow-down',
            2 => 'arrow-right', 
            3 => 'arrow-up',
            default => 'question'
        };
    }
}
