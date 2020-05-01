<answer :answer="{{$answer}}" inline-template>
    <div class="media post">
        <!--display votes controles-->
        {{-- @include('shared._votes', [
            'model' => $answer
        ]) --}}
        <vote :model="{{$answer}}" name="answer"></vote>

        <!--Display answer content-->
        <div class="media-body">

            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" rows="10" required></textarea>
                </div>
                <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                <button class="btn btn-outline-secondary" type="button" @click="cancel">Cancel</button>
            </form>

            <div v-else>
                <!--Display answer body-->
                {{-- {!! $answer->body_html !!} --}}
                <div v-html="bodyHtml"></div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="ml-auto bd-highlight q-buttons">
                            @can('update', $answer)
                                {{-- <a href="{{route('questions.answers.edit', [$question->id, $answer->id])}}" class="btn btn-outline-info btn-sm mr-2">Edit</a> --}}
                                <a @click.prevent="edit" class="btn btn-outline-info btn-sm mr-2">Edit</a>
                            @endcan
                            @can('delete', $answer)
                                {{-- <form action="{{route('questions.answers.destroy', [$question->id, $answer->id])}}" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick=" return confirm('Are you sure?')">Delete</button>
                                </form> --}}
                                <button @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                            @endcan
                        </div>
                    </div>

                    <div class="col-md-4"></div>

                    <!--Display person who answer question details using _auther.blade.php-->
                    {{-- <div class="col-md-4">
                        @include('shared._author', [
                            'model' => $answer,
                            'label' => 'Answered'
                        ])
                    </div> --}}

                    <!--Display person who answer question details using UserInfo.vue-->
                    <div class="col-md-4">
                        <user-info :model="{{$answer}}" label="Answered"></user-info>
                    </div>
                </div>

            </div>
        </div>
    </div>
</answer>
