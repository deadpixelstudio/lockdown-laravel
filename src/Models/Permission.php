<?php

namespace DeadPixelStudio\Lockdown\Models;

use Illuminate\Database\Eloquent\Model;
use DeadPixelStudio\Lockdown\Models\Group;

class Permission extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function path()
    {
        return "api/lockdown/permissions/{$this->id}";
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
