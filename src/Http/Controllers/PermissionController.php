<?php

namespace DeadPixelStudio\Lockdown\Http\Controllers;

use Illuminate\Routing\Controller;
use DeadPixelStudio\Lockdown\Models\Permission;
use DeadPixelStudio\Lockdown\Http\Requests\Permissions\StorePermission;
use DeadPixelStudio\Lockdown\Http\Requests\Permissions\UpdatePermission;
use DeadPixelStudio\Lockdown\Http\Requests\Permissions\DeletePermission;
use DeadPixelStudio\Lockdown\Resources\PermissionResource;

class PermissionController extends Controller
{
    public function index()
    {
        return PermissionResource::collection(
            Permission::all()
        );
    }

    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }


    public function store(StorePermission $permission)
    { 
        $permission = Permission::create(request(['name', 'slug']));

        return response()->json($permission, 201);
    }

    public function update(UpdatePermission $request, Permission $permission)
    {
        $permission->name = request('name');
        $permission->slug = request('slug');
        $permission->save();

        return response()->json($permission, 201);
    }

    public function destroy(DeletePermission $request, Permission $permission)
    {
        $permission->delete();
    }
}