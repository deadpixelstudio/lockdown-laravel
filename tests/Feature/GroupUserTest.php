<?php

namespace DeadPixelStudio\Lockdown\Tests\Feature;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Group;
use Illuminate\Foundation\Auth\User;

class GroupUserTest extends TestCase
{
    /** @test */
    function a_group_can_add_a_user()
    {
        $group = factory(Group::class)->create(['has_users' => 1]);

        $this->json('POST', "api/lockdown/groups/{$group->id}/users/", $this->testUser->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('group_user', [
            "group_id" => $group->id, 
            "user_id" => $this->testUser->id
        ]);
        $this->assertInstanceOf(User::class, $group->users()->first());
        $this->assertEquals($this->testUser->email, $group->users()->first()->email);
    }

    /** @test */
    function a_group_can_remove_a_user()
    {
        $group = factory(Group::class)->create(['has_users' => 1]);

        $this->json('POST', "api/lockdown/groups/{$group->id}/users/", $this->testUser->toArray());
        
        $this->json('DELETE', "api/lockdown/groups/{$group->id}/users/{$this->testUser->id}")
            ->assertOk();

        $this->assertDatabaseMissing('group_user', [
            "group_id" => $group->id, 
            "user_id" => $this->testUser->id
        ]);
        $this->assertEquals(0, $group->users->count());
    }
}