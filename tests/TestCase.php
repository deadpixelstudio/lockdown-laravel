<?php

namespace DeadPixelStudio\Lockdown\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DeadPixelStudio\Lockdown\Providers\LockdownServiceProvider;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use DeadPixelStudio\Lockdown\Tests\Setup\User;

class TestCase extends TestbenchTestCase
{
    use RefreshDatabase;

    protected $testUser;

    protected function setUp() :void
    {
        parent::setUp();

        $this->withFactories(dirname(__FILE__, 2).'/database/factories');

        $this->runTestMigrations($this->app);
    }

    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LockdownServiceProvider::class,
            NestedSetServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'lockdowntest');
        $app['config']->set('database.connections.lockdowntest', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }

    protected function runTestMigrations($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->softDeletes();
        });

        $this->testUser = User::create(['email' => 'user@test.com']);
    }
}