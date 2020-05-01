<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Question;
use App\Answer;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * this delete methods shoud be excute in reverse order of creation order
         * this delete methods can be replaced with migrate:fresh command
         * but you may need them for any reason like run seed individualy like [php artisan db:seed --class=UsersQuestionsAnswersTableSeeder]
         */
        \DB::table('answers')->delete();
        \DB::table('questions')->delete();
        \DB::table('users')->delete();

        //we dont make seperate factory for the questions because we have made relation between questions and users so we should use each() method as follwes
        factory(User::class, 3)->create()->each(function ($u){
            $u->questions()->saveMany(factory(Question::class, rand(1, 5))->make())
                ->each(function ($q){
                    $q->answers()->saveMany(factory(Answer::class, rand(1,5))->make());
                });
        });
    }
}
