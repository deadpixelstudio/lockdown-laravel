<?php

namespace DeadPixelStudio\Lockdown\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Group extends Model
{
    use NodeTrait;
    
    protected $guarded = [];

    public $timestamps = false;

    public function path()
    {
        return "api/lockdown/groups/{$this->id}";
    }

    public function parentGroup()
    {
        return $this->parent;
    }

    public function subGroups($recursive = false)
    {
        if (!$recursive) {
            return $this->children;
        }
        
        return $this->descendants;
    }

    public function addSubGroup($group)
    {
        return $this->appendNode($group);
    }
}
