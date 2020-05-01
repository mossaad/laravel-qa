@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h2>All Questions</h2>
                        <div>
                            <a href="{{route('questions.create')}}" class="btn btn-outline-secondary">Ask Question</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts._messages')
                    @forelse ($questions as $question)
                        @include('questions._excerpt')
                    @empty
                        <div class="alert alert-warning">
                            <strong>Sorry</strong> there is no questions available yet.
                        </div>

                    @endforelse

                    {{$questions->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
