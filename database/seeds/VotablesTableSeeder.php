<?php

use App\Answer;
use App\Question;
use App\User;
use Illuminate\Database\Seeder;

class VotablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('votables')->delete(); //for individual seeding [php artisan db:seed --class=VotablesTableSeeder]
        //assign all users in var
        $users = User::all();
        //count all users
        $numberOfUsers = $users->count();
        //define var that hold possible votes values
        $votes = [-1, 1]; //so when want to votedown say $votes[0] , voteup say $votes[1]

        foreach (Question::all() as $question)
        {
            //every single question can be voted up or down by at least one user randomly
            for($i = 0; $i < rand(1, $numberOfUsers); $i++)
            {
                //get the random user and assign to var
                $user = $users[$i];
                //make that user to vote the question using voteQuestion method in User model
                $user->voteQuestion($question , $votes[rand(0, 1)]);
            }
        }

        foreach (Answer::all() as $answer)
        {
            //every single question can be voted up or down by at least one user randomly
            for($i = 0; $i < rand(1, $numberOfUsers); $i++)
            {
                //get the random user and assign to var
                $user = $users[$i];
                //make that user to vote the question using voteQuestion method in User model
                $user->voteAnswer($answer , $votes[rand(0, 1)]);
            }
        }

    }
}
