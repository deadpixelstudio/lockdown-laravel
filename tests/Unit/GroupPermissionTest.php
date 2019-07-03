<?php

namespace DeadPixelStudio\Lockdown\Tests\Unit;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use DeadPixelStudio\Lockdown\Models\Permission;

class GroupPermissionTest extends TestCase
{
    /** @test */
    function a_permission_can_be_added_to_a_group()
    {
        $group = factory(Group::class)->create();

        $permission = factory(Permission::class)->create();

        $group->addPermission($permission);

        $this->assertEquals($permission->id, $group->permissions()->first()->id);
    }

    /** @test */
    function a_permission_can_be_removed_from_a_group()
    {
        $group = factory(Group::class)->create();

        $permission = factory(Permission::class)->create();

        $group->addPermission($permission);

        $group->removePermission($permission); 

        $this->assertDatabaseMissing('group_permission', [
            "group_id" => $group->id, 
            "permission_id" => $permission->id
        ]);
        $this->assertEquals(0, $group->permissions->count());
    }
}
