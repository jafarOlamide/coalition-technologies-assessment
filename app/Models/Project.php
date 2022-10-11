<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use UuidTrait, HasFactory;

    protected $guarded = [];


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
