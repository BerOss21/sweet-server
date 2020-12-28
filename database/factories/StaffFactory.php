<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Staff;
use Faker\Generator as Faker;

$factory->define(Staff::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'=>$faker->lastName ,
        'description' => $faker->text($maxNbChars = 120,$minNbChars = 50),
        'image' => $faker->imageUrl($width = 640, $height = 480),
        'facebook' => $faker->url,
        'instagram' => $faker->url 
    ];
});
