<?php

namespace DeadPixelStudio\Lockdown\Http\Controllers;

use Illuminate\Routing\Controller;
use DeadPixelStudio\Lockdown\Models\Group;
use DeadPixelStudio\Lockdown\Resources\GroupResource;
use DeadPixelStudio\Lockdown\Http\Requests\Groups\StoreGroup;
use DeadPixelStudio\Lockdown\Http\Requests\Groups\UpdateGroup;
use DeadPixelStudio\Lockdown\Http\Requests\Groups\DeleteGroup;

class GroupController extends Controller
{
    public function index()
    {
        return GroupResource::collection(
            Group::get()->toTree()
        );
    }

    public function show(Group $group)
    {
        return new GroupResource($group);
    }


    public function store(StoreGroup $request)
    { 
        $group = Group::create(request(['name', 'slug', 'parent_id', 'has_users']));

        return response()->json($group, 201);
    }

    public function update(UpdateGroup $request, Group $group)
    {
        $group->name = request('name');
        $group->slug = request('slug');
        $group->save();

        return response()->json($group, 201);
    }

    public function destroy(DeleteGroup $request, Group $group)
    {
        $group->delete();
    }
}