<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'ahoj',
        // 'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'api_token' => str_random(60)
    ];
});

$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'Name' => $faker->name,
        'ProjectDescription' => $faker->text($maxNbChars = 200),
        'TestingDescription' => $faker->text($maxNbChars = 200)
    ];
});

$factory->define(App\TestSuite::class, function (Faker\Generator $faker) {
    return [
        'Name' => $faker->name
    ];
});


$factory->define(App\TestCase::class, function (Faker\Generator $faker) {
    return [
        'Name' => $faker->name,
        'TestSuite_id' => 1
    ];
});

$factory->define(App\TestCaseHistory::class, function (Faker\Generator $faker) {
    return [
        'TestCaseOverview_id' => 1,
        'Version_id' => 1,
    ];
});
