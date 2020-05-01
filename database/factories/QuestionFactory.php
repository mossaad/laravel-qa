<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'title' => rtrim($faker->sentence(rand(5, 10)), "."),
        'body' => $faker->paragraphs(rand(3, 7), true), //true param to make new line between ech paragraph
        'views' => rand(0, 10),
        //'answers_count' => rand(0, 10), //canceled because we use boot method in answer model
        //'votes_count' => rand(-3, 10) //renamed by table-> RenameVotesOnQuestionsTable //canceled because we use VotablesTableSeeder
    ];
});
