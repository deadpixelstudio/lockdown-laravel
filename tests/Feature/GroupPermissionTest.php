<?php

namespace DeadPixelStudio\Lockdown\Tests\Feature;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Group;
use DeadPixelStudio\Lockdown\Models\Permission;

class GroupPermissionTest extends TestCase
{
    /** @test */
    function a_permission_can_be_added_to_a_group()
    {
        $group = factory(Group::class)->create();

        $permission = factory(Permission::class)->create();

        $this->json('POST', "api/lockdown/groups/{$group->id}/permissions/", $permission->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('group_permission', [
            "group_id" => $group->id, 
            "permission_id" => $permission->id
        ]);
        $this->assertInstanceOf(Permission::class, $group->permissions()->first());
        $this->assertEquals($permission->id, $group->permissions()->first()->id);
    }

    /** @test */
    function a_permission_can_be_removed_from_a_group()
    {
        $group = factory(Group::class)->create();

        $permission = factory(Permission::class)->create();

        $this->json('POST', "api/lockdown/groups/{$group->id}/permissions/", $permission->toArray());
        
        $this->json('DELETE', "api/lockdown/groups/{$group->id}/permissions/{$permission->id}")
            ->assertOk();

        $this->assertDatabaseMissing('group_permission', [
            "group_id" => $group->id, 
            "permission_id" => $permission->id
        ]);
        $this->assertEquals(0, $group->permissions->count());
    }
}