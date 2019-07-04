<?php

namespace DeadPixelStudio\Lockdown\Models\Traits;

use DeadPixelStudio\Lockdown\Models\Group;
use DeadPixelStudio\Lockdown\Models\Permission;
use Illuminate\Support\Collection;

trait Lockdown
{
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function joinGroup($group)
    {
        return $this->groups()->attach($group);
    }

    public function leaveGroup($group)
    {
        return $this->groups()->detach($group);
    }

    public function inGroup($group)
    {

        if (is_string($group))
        {
            return $this->groups->contains('slug', $group);
        }

        return $this->groups->contains('id', $group);
    }

    public function inAnyGroup(Array $groups)
    {
        foreach ($groups as $group)
        {
            if ($this->inGroup($group)) {
                return true;
            }
        }

        return false;
    }

    public function inAllGroups(Array $groups)
    {
        foreach ($groups as $group)
        {
            if(!$this->inGroup($group))
            {
                return false;
            }
        }

        return true;
    }

    public function hasPermission($requestedPermission)
    {
        $hasPermission = false;

        $permissionGroups = $this->allPermissionGroups();

        $permissionGroups->each(function ($group, $key) use (&$hasPermission, $requestedPermission) {

            $group->permissions->each(function ($permission, $key) use (&$hasPermission, $requestedPermission) {
                if($permission->slug == $requestedPermission)
                {
                    $hasPermission = true;
                }
            });

        });

        return $hasPermission;
    }

    public function hasAnyPermission(Array $permissions)
    {      
        foreach ($permissions as  $permission)
        {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(Array $permissions)
    {
        foreach ($permissions as  $permission)
        {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function allPermissionGroups()
    {
        $permissionGroups = $this->groups()->get();

        $this->groups()->each(function ($group, $key) use ($permissionGroups) {

            $groupAncestors = $group->getAncestors()->flatten();

            $groupAncestors->each(function ($ancestorGroup, $key) use ($permissionGroups) {
                $permissionGroups->push($ancestorGroup); 
            });
        });

        return $permissionGroups;
    }
}