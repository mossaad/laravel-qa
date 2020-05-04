<template>
    <div>
        <div class="row mt-4" v-cloak v-if="count > 0"> <!--v-cloak directive delay the apperance of elements until vue instances finish compilation then it will be removed-->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>{{title}}</h2>
                        </div>
                        <hr>

                        <!-- answer in binded answer is come from answer in v-for & v-for need the key or it will give mistack-->
                        <!--deleted event will remove this answer index from answers array-->
                        <answer @deleted="remove(index)" v-for="(answer, index) in answers" :answer="answer" :key="answer.id"></answer>

                        <div class="text-center mt-3" v-if="nextUrl">
                            <button @click.prevent="fetch(nextUrl)" class="btn btn-outline-secondary">Load more answers</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <new-answer @created="add" :question-id="question.id" ></new-answer>
    </div>
</template>>

<script>
import Answer from './Answer.vue';
import NewAnswer from './NewAnswer.vue';
import highlight from '../mixins/highlight.js';

    export default {
        props: ['question'],

        mixins: [highlight],

        data () {
            return {
                questionId: this.question.id,
                count: this.question.answers_count,
                answersIds: [],
                answers: [],
                nextUrl: null
            }
        },

        //created() called after the instance is created and used to fetch data from backend api
        created () {
            this.fetch(`/questions/${this.questionId}/answers`) //index route //and defining index method in AnswersController
        },

        methods: {
            
            add (answer) {
                this.answers.push(answer);
                this.count++;
                this.$nextTick(() => { //used to delay highlight method until pushing the new answer to answers array to the dom
                    this.highlight(`answer-${answer.id}`);
                })
            },

            remove (index) {
                //remove the item from answers array by utilizing javacript splice method
                this.answers.splice(index, 1);
                //decreased the count by the item removed
                this.count--;
            },

            fetch (endpoint) {

                this.answersIds = [];//before make ajax call reset answersIds array to make sure that every time make ajax call we donot have duplicate answer ids

                axios.get(endpoint)
                .then(({data}) => {  //extract or get data property from response object by using ({data})

                    this.answersIds = data.data.map(a => a.id); // collect all answers ids by map method and send them to answersIds array

                    this.answers.push(...data.data); //to merge data property array with answers array ussing es6 by (...)

                    this.nextUrl = data.next_page_url; //
                })
                .then(() => {

                    //get back answers from ajax request and loap each one of them and call highlight method for evry single answer using answersIds which hold all answers ids that called back from ajax request
                    this.answersIds.forEach(id => {
                        this.highlight(`answer-${id}`);
                    });
                })
            }
        },

        computed: {
            title () {
                return this.count + " " + (this.count > 1 ? 'Answers' : 'Answer')
            }
        },

        components: {
            Answer,
            NewAnswer
        }
    }
</script>
