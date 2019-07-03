<?php

namespace DeadPixelStudio\Lockdown\Tests\Unit;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupTest extends TestCase
{
    /** @test */
    function it_has_a_path()
    {
        $group = factory(Group::class)->create();

        $this->assertEquals("api/lockdown/groups/{$group->id}", $group->path());
    }

    /** @test */
    function a_group_has_users()
    {
        $group = factory(Group::class)->create();

        $this->assertInstanceOf(Collection::class, $group->users);
    }

    /** @test */
    function it_can_have_a_parent_group()
    {
        $parentGroup = factory(Group::class)->create(['name' => 'Parent Group']);
        $subGroup = factory(Group::class)->create(['name' => 'Sub Group']);
        $parentGroup->addSubGroup($subGroup);

        $this->assertCount(1, $parentGroup->subGroups());
        $this->assertEquals('Sub Group', $parentGroup->subGroups()->first()->name);
    }

    /** @test */
    public function it_can_retrieve_its_parent_group()
    {
        $group = factory(Group::class)->create([
            'name' => $name = 'Parent Group'
        ]);

        $group->addSubGroup(
            $subGroup = factory(Group::class)->create()
        );
        
        $this->assertEquals('Parent Group', $subGroup->parentGroup()->name);
    }

    /** @test */
    public function it_can_retrieve_its_direct_sub_groups()
    {
        $group = factory(Group::class)->create();

        $group->addSubGroup(
            $subGroup = factory(Group::class)->create([
                'name' => $name = 'Sub Group'
            ])
        );
        
        $this->assertEquals('Sub Group', $group->subGroups()->first()->name);
    }

    /** @test */
    public function it_can_retrieve_all_its_sub_groups_recursively()
    {
        $group = factory(Group::class)->create();

        $group->addSubGroup(
            $subGroup = factory(Group::class)->create([
                'name' => $name = 'Sub Group'
            ])
        );

        $subGroup->addSubGroup(
            $subSubGroup = factory(Group::class)->create([
                'name' => $name = 'Sub Sub Group'
            ])
        );
        
        $this->assertCount(2, $group->subGroups(true));
        $this->assertEquals('Sub Group', $group->subGroups()->first()->name);
    }
}
