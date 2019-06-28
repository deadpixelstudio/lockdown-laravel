<?php

namespace DeadPixelStudio\Lockdown\Tests\Feature;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Group;

class GroupManagementTest extends TestCase
{
    /** @test */
    function a_user_can_create_a_group()
    {
        $group = factory(Group::class)->raw();

        $this->json('POST', 'api/lockdown/groups', $group)
            ->assertStatus(201);;
        $this->assertDatabaseHas('groups', $group);
    }

    /** @test */
    function it_returns_a_group_if_found()
    {
        $this->withExceptionHandling();
        
        $group = factory(Group::class)->create();

        $this->json('GET', $group->path())
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $group->id
            ]);
    }

    /** @test */
    function it_fails_if_a_group_cannot_be_found()
    {
        $this->json('GET', 'api/lockdown/groups/non-existant-group')
            ->assertNotFound();
    }

    /** @test */
    function a_group_requires_a_name()
    {
        $group = factory(Group::class)->raw(['name' => '']);

        $this->json('POST', 'api/lockdown/groups', $group)
            ->assertStatus(422)
            ->assertJsonFragment([
                'name' => ['The name field is required.']
            ]);
    }
    
    /** @test */
    function a_group_requires_a_slug()
    {
        $group = factory(Group::class)->raw(['slug' => '']);

        $this->json('POST', 'api/lockdown/groups', $group)
            ->assertStatus(422)
            ->assertJsonFragment([
                'slug' => ['The slug field is required.']
            ]);
    }
}