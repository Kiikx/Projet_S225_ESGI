<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
    'name',
    'project_id', 
];

public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}

public function tasks()
{
    return $this->belongsToMany(\App\Models\Task::class);
}

}
