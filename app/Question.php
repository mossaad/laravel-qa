<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use VotableTrait;

    protected $guarded = [];

    /**
     * the accesories that will be included in json representaion
     * when echo the model instence in blade using vue compnenets in questions/show.blade.php
     * wen need to appends them
     */
    protected $appends = [
        'created_date', 'is_favorited', 'favorites_count', 'body_html'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //method used to convert question title to slug format wich called [mutator] wich start with set->ColumName->Attribute
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    /**
     * Accesories methods used to use attributes doesnot exist in our model or database table to use it in our view file
     * this method start with get->atrribute name in camel case->Attribute
     */
    public function getUrlAttribute()
    {
        return route('questions.show', $this->slug); //chnge $this->id to $this->slug to show the slug in url for seo
    }

    /**
     * Accesories method used to to get the creation date formate
     * diffForHumans() method used to get the creation date in (time ago) formate
     * formate('d/m/y') method will give (day/month/year) formate
    */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if($this->answers_count > 0){
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    /**
     * this method replaced by rout binding in routeServiceprovider.php
     */
    // //method used to overide what laravel using in routebinding instead of using the id in show route (in show method in QuestionController) to fetch the question from database
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }


    /**
     * we need to change answers column name to answers_count to prevent conflict with this relation
     * we used renaming table as(php artisan make:migration rename_answers_in_questions_table --table=questions)
     * to migrate renaming table we need to install dependency (composer require doctrine/dbal) and then migrate src(https://laravel.com/docs/5.8/migrations#modifying-columns)
     * and we change answers in question index blade, question factory, question policy, question model to answers_count
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('votes_count', 'DESC')->with('question');
    }

    /**
     * method used to invoke the on click function in AcceptAnswerController
     */
    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    //many to many relatio [user belongs to many favorite questions]
    public function favorites()
    {
        /**
         * specify the table name in the second argument,
         * third arg is foreign key of this model is (question_id) [optional]
         * fourth arg is foregin key of the model joining to  (user_id) [optional]
         * we use withTimestamps() method to fill created_at & updated_at columns in database table
         */
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    //method check whether the current question is favorited by the auth user [return true or false]
    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    //Accesore used to check whether the current question is favorited by the auth user
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    //Accesores used to count the number of favorites
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
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

    /**
     * Accesore used to get html markup used in questions show.blade.php
     * using purifier backage https://github.com/mewebstudio/Purifier
     * composer require mews/purifier
     * php artisan vendor:publish --provider="Mews\Purifier\PurifierServiceProvider"
     * purifier.php in config folder we can chang setting oh alloed html such as adding class to p -> p[style|class]
     * we can simply use clean() method to prevent dispaly tags and users from injecting bad tags
    */
    public function getBodyHtmlAttribute()
    {
        return clean($this->bodyHtml());
    }

    /**
     * used in question index.blade.php
     * display question body, using strip_tags method to prevent dispaly tags and users from injecting bad tags
     * with using strip_tags no need to use !! !! format
     * we will capsulate str_limit(strip_tags($question->body_html), 250) in aexcerpt Accesores
     */
    public function getExcerptAttribute()
    {
        return $this->excerpt(300);
    }

    public function excerpt($length)
    {
        return str_limit(strip_tags($this->bodyHtml), $length);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }
}
