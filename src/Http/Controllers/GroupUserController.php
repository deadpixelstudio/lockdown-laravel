<?php

namespace DeadPixelStudio\Lockdown\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use DeadPixelStudio\Lockdown\Models\Group;

class GroupUserController extends Controller
{
    public function store(Request $request, Group $group)
    { 
       $group->addUser(User::find($request->id));

       return response()->json($group, 201);
    }

    public function destroy(Group $group, User $user)
    {
        $group->removeUser($user);

        return response()->json($group);
    }
}