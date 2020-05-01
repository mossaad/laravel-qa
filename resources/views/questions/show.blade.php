@extends('layouts.app')
@section('content')
<div class="container">
    {{-- <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!--display the queston header-->
                    <div class="card-title">
                        <div class="d-flex bd-highlight">
                            <h2 class="mr-auto bd-highlight">{{$question->title}}</h2>
                            <div class="ml-auto bd-highlight q-buttons">
                                <a href="{{route('questions.index')}}" class="btn btn-outline-secondary">Back to all Questions</a>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!--Display question content-->
                    <div class="media">
                        <!--display votes controles-->
                        {{-- @include('shared._votes', [
                            'model' => $question
                        ]) --}}
                        {{-- <vote :model="{{$question}}" name="question"></vote>

                        <!--Display question body-->
                        <div class="media-body">
                            <!--Display question body-->
                            {!! $question->body_html !!}

                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <!--Display person who ask question details using _auther.blade.php-->
                                    {{-- @include('shared._author', [
                                        'model' => $question,
                                        'label' => 'Asked'
                                    ]) --}}

                                    <!--Display person who ask question details using UserInfo.vue-->
                                    {{-- <user-info :model="{{ $question }}" label="Asked"></user-info>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}

    {{-- <question :question="{{$question}}"></question> --}} {{--///////////////////will be moved to parent component <question-page></question-page> component/////////// --}}

    <!--Display Answers For the questions-->
    {{-- @include('answers._index', [
        'answers' => $question->answers,
        'answersCount' => $question->answers_count
    ]); --}}

    {{-- <answers :answers="{{$question->answers}}" :count="{{$question->answers_count}}"></answers> --}}

    {{-- <answers :question="{{$question}}"></answers> --}} {{--///////////////////will be moved to parent component <question-page></question-page> component////////////////// --}}

    <!--Display Create Answer Form For the questions-->
    {{-- @include('answers._create') --}} <!--replaced with NewAnswer.vue in Answers.vue-->

    <question-page :question="{{ $question }}"></question-page>

</div>

@endsection
