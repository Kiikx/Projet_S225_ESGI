<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

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

}
