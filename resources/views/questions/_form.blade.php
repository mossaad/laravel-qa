@csrf
<div class="form-group">
    <label for="question-title">Qestion title</label>
    <input type="text" id="question-title" class="form-control {{ $errors->has('title')? 'is-invalid' : '' }}" name="title" value="{{isset($question) ? $question->title : old('title')}}">
    @if($errors->has('title'))
        <div class="invalid-feedback">
            <strong>{{$errors->first('title')}}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <label for="question-body">Explain your question </label>
    <textarea id="question-body" class="form-control {{ $errors->has('body')? 'is-invalid' : '' }}" name="body" rows="10">{{isset($question) ? $question->body : old('body')}}</textarea>
    @if($errors->has('body'))
        <div class="invalid-feedback">
            <strong>{{$errors->first('body')}}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <button type="submit" class="btn btn-outline-primary btn-lg">{{$buttonText}}</button>
</div>
