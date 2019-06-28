<?php

use DeadPixelStudio\Lockdown\Models\Group;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->sentence(rand(1,2)),
        'slug' => str_slug($name),
        'has_users' => 0,
    ];
});
