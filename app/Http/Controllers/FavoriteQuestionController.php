<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class FavoriteQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question)
    {
        //attach the question or make it favorited by current user
        $question->favorites()->attach(auth()->id());

        if(request()->expectsJson()){
            return response()->json(null, 204); //null to return no content and 204 indicate that the action excuted successfully but no content to return
        }

        return back();
    }

    public function destroy(Question $question)
    {
        //detach the question or make it unfavorited by current user
        $question->favorites()->detach(auth()->id());

        if(request()->expectsJson()){
            return response()->json(null, 204); //null to return no content and 204 indicate that the action excuted successfully but no content to return
        }

        return back();
    }
}
