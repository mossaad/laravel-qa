<?php
//$ php artisan make:controller QuestionsController --resource --model=Question

namespace App\Http\Controllers;

use App\Http\Requests\AskQuestionRequest;
use App\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * composer require barryvdh/laravel-debugbar --dev
         * we used this backage to debug the query problem to prevent repeat the query wich cause lazy loading
         * this backage do the job of laravel telescope
         * so we used with('user') user relation to collect the ids of all user in one query to prevent the rpeatation
         */
        $questions = Question::with('user')->latest()->paginate(10);
        return view('questions.index', compact('questions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title', 'body'));
        return redirect(route('questions.index'))->with('success', 'Your Question has added successfully :)');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize("update", $question);
        $question->update($request->only('title', 'body'));

        if($request->expectsJson())
        {
            return response()->json([
                'message' => "Your Question has updated successfully :)",
                'body_html' => $question->body_html
            ]);
        }

        return redirect(route('questions.index'))->with('success', 'Your Question has updated successfully :)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->authorize("delete", $question);
        $question->delete();

        if(request()->expectsJson())
        {
            return response()->json([
                'message' => "Your Question has Deleted successfully :)"
            ]);
        }

        return redirect(route('questions.index'))->with('success', 'Your Question has Deleted successfully :)');
    }
}
