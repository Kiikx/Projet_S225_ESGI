<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{

public function tasks()
{
    return $this->hasMany(\App\Models\Task::class);
}

}
