<div class="media post">

    <div class="d-flex flex-column counters">
        <div class="vote">
            <strong>{{$question->votes_count}}</strong> {{str_plural('vote', $question->votes_count)}}
        </div>
        <div class="status {{$question->status}}">
            <strong>{{$question->answers_count}}</strong> {{str_plural('answer', $question->answers_count)}}
        </div>
        <div class="view">
            {{$question->views . " " . str_plural('view', $question->views)}}
        </div>
    </div>

    <div class="media-body">
        <div class="d-flex bd-highlight">
            <h3 class="mt-0 mr-auto bd-highlight"><a href="{{$question->url}}">{{ $question->title}}</a></h3>
            <div class="ml-auto bd-highlight q-buttons">
                @can('update', $question)
                    <a href="{{route('questions.edit', $question->id)}}" class="btn btn-outline-info btn-sm mr-2">Edit</a>
                @endcan
                @can('delete', $question)
                    <form action="{{route('questions.destroy', $question->id)}}" method="POST" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick=" return confirm('Are you sure?')">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
        <p class="lead">
            Asked by
            <a href="{{$question->user->url}}">{{$question->user->name}}</a>
            <small class="text-muted">{{$question->created_date}}</small>
        </p>

        <!--display question body, using strip_tags method to prevent dispaly tags and users from injecting bad tags-->
        <!--with using strip_tags no need to use !! !! format-->
        <!--we will capsulate str_limit(strip_tags($question->body_html), 250) in aexcerpt Accesores in Question model-->
        <div class="excerpt">{{ $question->excerpt}}</div>
    </div>
</div>
