<?php

use App\Models\Task;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Task::class, function (Faker $faker) {
    // Unfortunately faker doesn't have a dateBetween at the moment, only a dateTimeBetween method
    $taskDate = date('Y-m-d', strtotime('now -' . $this->faker->numberBetween(0, 7) . ' days'));

    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'duration' => rand(5, 120),
        'user_id' => 1,
        'date' => $taskDate
    ];
});
