<?php

use App\Models\Region;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Region::class, function (Faker $faker) {
    $active = $faker->boolean;

    return [
        'name' => $name = $faker->unique()->city,
        'slug' => str_slug($name),
        'parent_id' => null,
    ];
});
