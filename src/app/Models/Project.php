<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

protected $fillable = ['name', 'description', 'owner_id'];

public function owner()
{
    return $this->belongsTo(\App\Models\User::class, 'owner_id');
}

public function members()
{
    return $this->belongsToMany(\App\Models\User::class);
}

public function tasks()
{
    return $this->hasMany(\App\Models\Task::class);
}

public function categories()
{
    return $this->hasMany(\App\Models\Category::class);
}

public function statuses()
{
    return $this->hasMany(Status::class);
}

}
