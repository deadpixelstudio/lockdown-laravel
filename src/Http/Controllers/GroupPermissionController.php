<?php

namespace DeadPixelStudio\Lockdown\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use DeadPixelStudio\Lockdown\Models\Group;
use DeadPixelStudio\Lockdown\Models\Permission;

class GroupPermissionController extends Controller
{
    public function store(Request $request, Group $group)
    { 
       $group->addPermission(Permission::find($request->id));

       return response()->json($group, 201);
    }

    public function destroy(Group $group, Permission $permission)
    {
        $group->removePermission($permission);

        return response()->json($group);
    }
}