<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use VotableTrait;

    protected $guarded = [];

    /**
     * the accesories that will be included in json representaion
     * when echo the model instence in blade using vue compnenets in answers/_answer.blade.php
     * wen need to appends them
     */
    protected $appends = [
        'created_date', 'body_html', 'is_best'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Accesore used to get html markup used in answers index.blade.php
     * using purifier backage https://github.com/mewebstudio/Purifier
     * composer require mews/purifier
     * php artisan vendor:publish --provider="Mews\Purifier\PurifierServiceProvider"
     * purifier.php in config folder we can chang setting oh alloed html such as adding class to p -> p[style|class]
     * we can simply use clean() method to prevent dispaly tags and users from injecting bad tags
    */
    public function getBodyHtmlAttribute()
    {
        return clean(\Parsedown::instance()->text($this->body));
    }

    //Accesories method used to connect number of question answers with answer_count in question table at create and delet answer
    public static function boot()
    {
        parent::boot();
        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });
        static::deleted(function ($answer) {
            $answer->question->decrement('answers_count');
            /**we use another method by making table for best answer id foreign
             * php artisan make:migration add_foreign_best_answer_id_to_questions_table --table=questions
             */
            //Check if this deleted answer is the best answer
            // if($answer->question->best_answer_id == $answer->id){
            //     $answer->question->best_answer_id = NULL;
            //     $answer->question->save();
            // }
        });
    }

    //Accesories method used to get the creation date formate
    public function getCreatedDateAttribute()
    {
        //diffForHumans() method used to get the creation date in (time ago) formate
        //formate('d/m/y') method will give (day/month/year) formate
        return $this->created_at->diffForHumans();
    }

    //Accesories method used to add class vote-accepted if this answer is best answer
    public function getStatusAttribute()
    {
        return $this->isBest() ? 'vote-accepted' : '';
    }

    //Accesories method used to allowe other users they are not owner of the question to see the check mark of the best anser
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    //method passed in status and is_best accesores
    public function isBest()
    {
        return $this->id == $this->question->best_answer_id;
    }

    /**
     * polymirphed relationship [equal to many to many relationship but we use one table for all models used in this relation]
     * we will use two relation because the user will have relation with question and relation with answer
     * user can vote many answer, user can vote many question
     * answer can voted by many users, question can voted by many users
    */

    /**moved to VotableTrait.php and will be called above */
    // public function votes()
    // {
    //     //second arg is singuler form of table name, with that elquent we will recognize that the table name is votables and they will be votable id and votable type
    //     return $this->morphToMany(User::class, 'votable');
    // }

    // public function upVotes()
    // {
    //     return $this->votes()->wherePivot('vote', 1);
    // }

    // public function downVotes()
    // {
    //     return $this->votes()->wherePivot('vote', -1);
    // }
}
