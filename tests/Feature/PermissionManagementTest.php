<?php

namespace DeadPixelStudio\Lockdown\Tests\Feature;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Permission;

class PermissionManagementTest extends TestCase
{
    /** @test */
    function a_user_can_create_a_permission()
    {
        $permission = factory(Permission::class)->raw();

        $this->json('POST', 'api/lockdown/permissions', $permission)
            ->assertStatus(201);

        $this->assertDatabaseHas('permissions', $permission);
    }

    /** @test */
    function a_user_can_update_a_permission()
    {
        $permission = factory(Permission::class)->create();

        $this->json('PATCH', $permission->path(), ['name' => 'Updated Name', 'slug' => 'updated_name'])
            ->assertJsonFragment([
                'name' => 'Updated Name'
            ]);
    }

    /** @test */
    function a_user_can_delete_a_permission()
    {
        $permission = factory(Permission::class)->create();

        $this->json('DELETE', $permission->path());
        
        $this->assertDatabaseMissing('permissions', $permission->toArray());
    }

    /** @test */
    public function it_returns_a_collection_of_permissions()
    {
        $permissions = factory(Permission::class, 2)->create();

        $response = $this->json('GET', 'api/lockdown/permissions');

        $permissions->each(function ($permission) use ($response) {
            $response->assertJsonFragment([
                'slug' => $permission->slug
            ]);
        });
    }

    /** @test */
    function it_returns_a_permission_if_found()
    {       
        $permission = factory(Permission::class)->create();

        $this->json('GET', $permission->path())
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $permission->id
            ]);
    }

    /** @test */
    function it_fails_if_a_permission_cannot_be_found()
    {
        $this->json('GET', 'api/lockdown/permissions/non-existant-permission')
            ->assertNotFound();
    }

    /** @test */
    function a_permission_requires_a_name()
    {
        $permission = factory(Permission::class)->raw(['name' => '']);

        $this->json('POST', 'api/lockdown/permissions', $permission)
            ->assertStatus(422)
            ->assertJsonFragment([
                'name' => ['The name field is required.']
            ]);
    }

    /** @test */
    function a_permission_requires_a_slug()
    {
        $permission = factory(Permission::class)->raw(['slug' => '']);

        $this->json('POST', 'api/lockdown/permissions', $permission)
            ->assertStatus(422)
            ->assertJsonFragment([
                'slug' => ['The slug field is required.']
            ]);
    }
}