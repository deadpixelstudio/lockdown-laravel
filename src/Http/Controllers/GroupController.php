<?php

namespace DeadPixelStudio\Lockdown\Http\Controllers;

use Illuminate\Routing\Controller;
use DeadPixelStudio\Lockdown\Models\Group;
use DeadPixelStudio\Lockdown\Resources\GroupResource;

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


    public function store()
    { 
        request()->validate(['name' => 'required', 'slug' => 'required']);

        $group = Group::create(request(['name', 'slug', 'parent_id', 'has_users']));

        return response()->json($group, 201);
    }
}