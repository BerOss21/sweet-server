<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Food;
use Faker\Generator as Faker;

$factory->define(Food::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->text($maxNbChars = 300,$minNbChars = 100),
        'image' => $faker->imageUrl($width = 640, $height = 480),
        'category_id' => $faker->numberBetween($min = 1, $max = 3),
        'price' => $faker->numberBetween($min = 50, $max = 500)
       
    ];
});
