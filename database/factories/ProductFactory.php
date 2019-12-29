<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'name'=>$faker->company,
        'slug'=>str_slug($faker->company),
        'price'=>random_int(10,100)
    ];
});
