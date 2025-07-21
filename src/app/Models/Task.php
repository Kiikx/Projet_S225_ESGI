<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
protected $fillable = [
    'title',
    'description',
    'project_id',
    'priority_id',
    'creator_id',
    'status_id',
    'completed_at',
    'deadline',
];

protected $casts = [
    'completed_at' => 'datetime',
    'deadline' => 'datetime',
];


public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}

public function creator()
{
    return $this->belongsTo(\App\Models\User::class, 'creator_id');
}

public function categories()
{
    return $this->belongsToMany(\App\Models\Category::class);
}

public function priority()
{
    return $this->belongsTo(\App\Models\Priority::class);
}

/**
 * Une tâche peut être assignée à plusieurs utilisateurs
 */
public function assignees()
{
    return $this->belongsToMany(User::class)->withTimestamps();
}

public function status()
{
    return $this->belongsTo(Status::class);
}


}
