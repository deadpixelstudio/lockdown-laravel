<?php

namespace DeadPixelStudio\Lockdown\Tests\Unit;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use DeadPixelStudio\Lockdown\Models\Permission;

class LockdownTraitTest extends TestCase
{
    /** @test */
    function it_has_groups()
    {
        $this->assertInstanceOf(Collection::class, $this->testUser->groups);
    }

    /** @test */
    function it_can_join_a_group()
    {
        $group = factory(Group::class)->create();

        $this->testUser->joinGroup($group->id);

        $this->assertEquals($group->id, $this->testUser->groups->first()->id);
    }

    /** @test */
    function it_can_leave_a_group()
    {
        $group = factory(Group::class)->create();

        $this->testUser->joinGroup($group->id);

        $this->assertEquals($group->id, $this->testUser->groups->first()->id);

        $this->testUser->leaveGroup($group->id);

        $this->testUser->refresh();

        $this->assertEquals(0, $this->testUser->groups->count());
    }

    /** @test */
    function its_inclusion_in_a_group_can_be_checked_by_slug()
    {
        $group = factory(Group::class)->create();
        $this->testUser->joinGroup($group);

        $this->assertTrue($this->testUser->inGroup($group->slug));
    }

    /** @test */
    function its_inclusion_in_a_group_can_be_checked_by_id()
    {
        $group = factory(Group::class)->create();
        $this->testUser->joinGroup($group);

        $this->assertTrue($this->testUser->inGroup($group->id));
    }

    /** @test */
    function its_inclusion_in_any_of_a_set_of_groups_can_be_checked_by_slug()
    {
        $group = factory(Group::class)->create();
        $this->testUser->joinGroup($group);

        $this->assertFalse($this->testUser->inAnyGroup(['some-test-group', 'another-test-group']));
        $this->assertTrue($this->testUser->inAnyGroup(['some-test-group', $group->slug, 'another-test-group']));
    }

    /** @test */
    function its_inclusion_in_any_of_a_set_of_groups_can_be_checked_by_id()
    {
        $group = factory(Group::class)->create();
        $this->testUser->joinGroup($group);

        $this->assertFalse($this->testUser->inAnyGroup(['some-test-group', 3]));
        $this->assertTrue($this->testUser->inAnyGroup(['some-test-group', $group->id, 3]));
    }

    /** @test */
    function its_inclusion_in_all_of_a_set_of_groups_can_be_checked_by_slug()
    {
        $groups = factory(Group::class, 3)->create();

        $this->testUser->joinGroup($groups->pluck('id')->toArray());

        $this->assertFalse($this->testUser->inAllGroups(['some-test-group', 'another-test-group']));
        $this->assertTrue($this->testUser->inAllGroups($groups->pluck('slug')->toArray()));
    }

    /** @test */
    function its_inclusion_in_all_of_a_set_of_groups_can_be_checked_by_id()
    {
        $groups = factory(Group::class, 3)->create();

        $this->testUser->joinGroup($groups->pluck('id')->toArray());

        $this->assertFalse($this->testUser->inAllGroups([1, 2, 999]));
        $this->assertTrue($this->testUser->inAllGroups($groups->pluck('id')->toArray()));
    }

    /** @test */
    function it_can_get_all_its_permission_groups()
    {
        $group1 = factory(Group::class)->create(['name' => 'Group1']);
        $group2 = factory(Group::class)->create(['name' => 'Group2']);
        $group3 = factory(Group::class)->create(['name' => 'Group3']);
        
        $group1->addSubGroup($group2);
        $group2->addSubGroup($group3);

        $group4 = factory(Group::class)->create(['name' => 'Group4']);
        $group5 = factory(Group::class)->create(['name' => 'Group5']);
        $group6 = factory(Group::class)->create(['name' => 'Group6']);

        $group4->addSubGroup($group5);
        $group5->addSubGroup($group6);

        $this->testUser->joinGroup($group3);
        $this->testUser->joinGroup($group6);

        $this->assertEquals(6, $this->testUser->allPermissionGroups()->count());     
    }

    /** @test */
    function it_can_be_checked_for_a_permission()
    {
        $group = factory(Group::class)->create();
        $permission = factory(Permission::class)->create();
        $group->addPermission($permission);
        $this->testUser->joinGroup($group);

        $this->assertFalse($this->testUser->hasPermission('none-assigned-permission'));
        $this->assertTrue($this->testUser->hasPermission($permission->slug));
    }

    /** @test */
    function it_can_be_checked_for_any_permission_in_a_set_of_permissions()
    {
        $group = factory(Group::class)->create();
        $permission = factory(Permission::class)->create();
        $group->addPermission($permission);
        $this->testUser->joinGroup($group);

        $this->assertFalse($this->testUser->hasAnyPermission([
            'none-assigned-permission', 
            'another-none-assigned-permission'
        ]));
        $this->assertTrue($this->testUser->hasAnyPermission([
            'none-assigned-permission', 
            $permission->slug
        ]));
    }

    /** @test */
    function it_can_be_checked_for_all_permissions_in_a_set_of_permissions()
    {
        $group = factory(Group::class)->create();
        $permissions = factory(Permission::class, 3)->create();
        $group->addPermission($permissions);
        $this->testUser->joinGroup($group);

        $this->assertFalse($this->testUser->hasAllPermissions([
            'none-assigned-permission', 
            'another-none-assigned-permission'
        ]));
        $this->assertTrue($this->testUser->hasAllPermissions([
            $permissions[0]->slug, 
            $permissions[1]->slug, 
            $permissions[2]->slug
        ]));
    }

    /** @test */
    function it_receives_permissions_from_all_ancestor_groups()
    {
        $group1 = factory(Group::class)->create(['name' => 'Group1']);
        $group2 = factory(Group::class)->create(['name' => 'Group2']);
        $group3 = factory(Group::class)->create(['name' => 'Group3']);

        $group1->addSubGroup($group2);
        $group2->addSubGroup($group3);

        $directPermission = factory(Permission::class)->create(['slug' => 'direct-permission']);
        $parentPermission = factory(Permission::class)->create(['slug' => 'parent-permission']);
        $grandparentPermission = factory(Permission::class)->create(['slug' => 'grandparent-permission']);

        $group1->addPermission($grandparentPermission);
        $group2->addPermission($parentPermission);
        $group3->addPermission($directPermission);
        
        $this->testUser->joinGroup($group3);

        $this->assertTrue($this->testUser->hasAllPermissions([
            $directPermission->slug,
            $parentPermission->slug,
            $grandparentPermission->slug
        ]));
    }
}
