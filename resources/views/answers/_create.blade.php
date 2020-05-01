<!--Display Create Answer Form For the questions-->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>Your answer</h2>
                    </div>
                    <hr>
                    <form action="{{route('questions.answers.store', $question->id)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control {{$errors->has('body') ? 'is-invalid' : '' }}" name="body"  cols="30" rows="7"></textarea>
                            @if($errors->has('body'))
                                <div class="invalid-feedback">
                                    <strong>{{$errors->first('body')}}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="form-gruop">
                            <button type="submit" class="btn btn-lg btn-outline-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


