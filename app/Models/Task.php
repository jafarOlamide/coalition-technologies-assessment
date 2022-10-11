<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use UuidTrait, HasFactory, SoftDeletes;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
