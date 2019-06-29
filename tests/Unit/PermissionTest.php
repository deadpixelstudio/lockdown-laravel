<?php

namespace DeadPixelStudio\Lockdown\Tests\Unit;

use DeadPixelStudio\Lockdown\Tests\TestCase;
use DeadPixelStudio\Lockdown\Models\Permission;

class PermissionTest extends TestCase
{
    /** @test */
    function it_has_a_path()
    {
        $permission = factory(Permission::class)->create();

        $this->assertEquals("api/lockdown/permissions/{$permission->id}", $permission->path());
    }
}