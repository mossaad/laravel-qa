<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * the accesories that will be included in json representaion
     * when echo the model instence in blade
     * wen need to appends them
     */
    protected $appends = [
        'url', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //question relation
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Accessories method used to get the user name link
     */
    public function getUrlAttribute()
    {
        //return route('questions.show', $this->id);
        return '#';
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * get the user email image using gravatar
     */
    public function getAvatarAttribute()
    {
        $email = $this->email;
        $size = 32;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;

    }

    //many to many relatio [user belongs to many favorite questions]
    public function favorites()
    {
        /**
         * specify the table name in the second argument,
         * third arg is foreign key of this model is (user_id) [optional] can name it author_id,
         * fourth arg is foregin key of the model joining to  (question_id) [optional]
         * we use withTimestamps() method to fill created_at & updated_at columns in database table
         */
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps();
    }

    /**
     * polymirphed relationship [equal to many to many relationship but we use one table for all models used in this relation]
     * we will use two relation because the user will have relation with question and relation with answer
     * user can vote many answer, user can vote many question
     * answer can voted by many users, question can voted by many users
    */
    public function voteQuestions()
    {
        //second arg is singuler form of table name, with that elquent we will recognize that the table name is votables and they will be votable id and votable type
        return $this->morphedByMany(Question::class, 'votable');
    }

    public function voteAnswers()
    {
        //second arg is singuler form of table name, with that elquent we will recognize that the table name is votables and they will be votable id and votable type
        return $this->morphedByMany(Answer::class, 'votable');
    }

    //method to get the votes of this user on the question
    public function voteQuestion(Question $question, $vote)
    {
        // //check if this user vote on the question before and if this vote exists
        // if($this->voteQuestions()->where('votable_id', $question->id)->exists())
        // {
        //     //update the existing vote
        //     $this->voteQuestions()->updateExistingPivot($question, ['vote' => $vote]);
        // } else {
        //     //if not exists attach the vote on the question
        //     $this->voteQuestions()->attach($question, ['vote' => $vote]);
        // }
        // //refresh the question with the new votes by votes relation in question model
        // $question->load('votes');

        // //count votes up and down
        // $downVotes = (int) $question->downVotes()->sum('vote'); //downVotes() is method in question model
        // $upVotes = (int) $question->upVotes()->sum('vote'); //upVotes() is method in question model
        // $question->votes_count = $upVotes + $downVotes;
        // $question->save();

        $voteQuestions = $this->voteQuestions();
        return $this->_vote($voteQuestions, $question, $vote); //return added for vue integration
    }

    //method to get the votes of this user on the answer
    public function voteAnswer(Answer $answer, $vote)
    {
        // //check if this user vote on the answer before and if this vote exists
        // if($this->voteAnswers()->where('votable_id', $answer->id)->exists())
        // {
        //     //update the existing vote
        //     $this->voteAnswers()->updateExistingPivot($answer, ['vote' => $vote]);
        // } else {
        //     //if not exists attach the vote on the answer
        //     $this->voteAnswers()->attach($answer, ['vote' => $vote]);
        // }
        // //refresh the answer with the new votes by votes relation in answer model
        // $answer->load('votes');

        // //count votes up and down
        // $downVotes = (int) $answer->downVotes()->sum('vote'); //downVotes() is method in answer model
        // $upVotes = (int) $answer->upVotes()->sum('vote'); //upVotes() is method in answer model
        // $answer->votes_count = $upVotes + $downVotes;
        // $answer->save();
        $voteAnswers = $this->voteAnswers();
        return $this->_vote($voteAnswers, $answer, $vote); //return added for vue integration
    }

    private function _vote($relationship, $model, $vote)
    {
        //check if this user vote on the answer before and if this vote exists
        if($relationship->where('votable_id', $model->id)->exists())
        {
            //update the existing vote
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        } else {
            //if not exists attach the vote on the answer
            $relationship->attach($model, ['vote' => $vote]);
        }
        //refresh the answer with the new votes by votes relation in answer model
        $model->load('votes');

        //count votes up and down
        $downVotes = (int) $model->downVotes()->sum('vote'); //downVotes() is method in answer model
        $upVotes = (int) $model->upVotes()->sum('vote'); //upVotes() is method in answer model
        $model->votes_count = $upVotes + $downVotes;
        $model->save();

        return $model->votes_count; //added for vue integration
    }
}
