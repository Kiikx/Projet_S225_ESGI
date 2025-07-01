<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    'title',
    'description',
    'due_date',
    'status',
    'project_id',
    'priority_id',
    'category_id',
    'creator_id',
    'assigned_to_id',
];


public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}

public function creator()
{
    return $this->belongsTo(\App\Models\User::class, 'creator_id');
}

public function category()
{
    return $this->belongsTo(\App\Models\Category::class);
}

public function priority()
{
    return $this->belongsTo(\App\Models\Priority::class);
}

public function assignee()
{
    return $this->belongsTo(User::class, 'assigned_to_id');
}


}
