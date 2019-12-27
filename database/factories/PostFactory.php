<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title'=> $faker->sentence(5),
        'content'=>$faker->paragraphs(4,true),
        'primary_image'=>'/images/primary_image.jpg',
        'thumbnail_image'=>'/images/thumbnail_image.jpg',
        'slug'=>$faker->sentence(5),
        'author'=>$faker->name,
    ];
});
