<?php
//php artisan make:policy AnswerPolicy --model=Question [then need to registered in AuthServiceProvider.php]
namespace App\Policies;

use App\User;
use App\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can update the question.
     *
     * @param  \App\User  $user
     * @param  \App\Question  $question
     * @return mixed
     */
    public function update(User $user, Question $question)
    {
        return $user->id == $question->user_id;
    }

    /**
     * Determine whether the user can delete the question.
     *
     * @param  \App\User  $user
     * @param  \App\Question  $question
     * @return mixed
     */
    public function delete(User $user, Question $question)
    {
        return $user->id == $question->user_id && $question->answers_count < 1; //that is mean questions has answers canot be deleted
    }
}
