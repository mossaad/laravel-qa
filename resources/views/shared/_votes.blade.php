@if($model instanceof App\Question)
    @php
        $name = 'question';
        $firstURLSegment = 'questions';
    @endphp
@elseif($model instanceof App\Answer)
    @php
        $name = 'answer';
        $firstURLSegment = 'answers';
    @endphp
@endif

@php
    $formId = $name . "-" . $model->id;
    $formAction = $firstURLSegment ."/". $model->id;
@endphp

<!--display votes controles-->
<div class="d-flex flex-column vote-controles">

    <!--display votes-up-->
    <a title="This {{$name}} is useful"
        class="vote-up {{Auth::guest() ? 'off' : ''}}"
        onclick="event.preventDefault(); document.getElementById('up-vote-{{ $formId }}').submit()">
        <i class="fas fa-caret-up fa-3x"></i>
    </a>
    <form id="up-vote-{{ $formId }}" action="/{{ $formAction }}/vote" method="post">
        @csrf
        <input type="hidden" name="vote" value="1">
    </form>
    <!--display votes_count-->
    <span class="vote-count">{{$model->votes_count}}</span>

    <!--display votes-down-->
    <a title="This {{$name}} is not useful"
        class="vote-down {{Auth::guest() ? 'off' : ''}}"
        onclick="event.preventDefault(); document.getElementById('down-vote-{{ $formId }}').submit()">
        <i class="fas fa-caret-down fa-3x"></i>
    </a>
    <form id="down-vote-{{ $formId }}" action="/{{ $formAction }}/vote" method="post">
        @csrf
        <input type="hidden" name="vote" value="-1">
    </form>

    <!--Mark this question as favorite-->
    @if($model instanceof App\Question)
        {{-- @include('shared._favorite', [
            'model' => $model
        ]) --}}
        <favorite :question="{{$model}}"></favorite>

    <!--Mark the answer as best answert-->
    @elseif($model instanceof App\Answer)
        {{-- @include('shared._accept', [
            'model' => $model
        ]) --}}
        <accept :answer="{{$model}}"></accept>
    @endif

</div>
