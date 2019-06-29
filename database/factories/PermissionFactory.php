<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use DeadPixelStudio\Lockdown\Models\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->sentence(rand(1,2)),
        'slug' => str_slug($name),
    ];
});
