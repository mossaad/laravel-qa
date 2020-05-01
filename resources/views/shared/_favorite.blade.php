<!--Mark this question as favorite-->
<a title="Click to mark as favorit question (click again to undo)"
class="{{Auth::guest() ? 'off' : ($model->is_favorited ? 'favorited' : '')}} mt-2"
onclick="event.preventDefault(); document.getElementById('favorite-question-{{$model->id}}').submit()">
    <i class="fas fa-star fa-2x"></i>
</a>
<form id="favorite-question-{{$model->id}}" action="/questions/{{$model->id}}/favorites" method="POST">
    @csrf
    @if($model->is_favorited)
        @method('DELETE')
    @endif
</form>
<!--display queston favorites_count-->
<span class="favorite-count">{{$model->favorites_count}}</span>
