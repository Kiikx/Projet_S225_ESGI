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

/**
 * Les catégories sont maintenant globales, plus de relation directe avec les projets
 */

public function statuses()
{
    return $this->hasMany(Status::class);
}

public function invitations()
{
    return $this->hasMany(ProjectInvitation::class);
}

public function pendingInvitations()
{
    return $this->hasMany(ProjectInvitation::class)->pending();
}

}
