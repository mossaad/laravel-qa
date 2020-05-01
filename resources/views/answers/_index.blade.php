<!--Display Answers For the questions-->
@if($answersCount >0)
    <div class="row mt-4" v-cloak> <!--v-cloak directive delay the apperance of elements until vue instances finish compilation then it will be removed-->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>{{$answersCount . " " . str_plural('Answer', $answersCount)}}</h2>
                    </div>
                    <hr>

                    @include('layouts._messages')

                    @foreach ($answers as $answer)

                        @include('answers._answer')

                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif


