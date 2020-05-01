<template>

    <div class="media post">
        <vote :model="answer" name="answer"></vote>

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
                <div v-html="bodyHtml"></div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="ml-auto bd-highlight q-buttons">
                            <a v-if="authorize('modify', answer)"  @click.prevent="edit" class="btn btn-outline-info btn-sm mr-2">Edit</a>
                            <button v-if="authorize('modify', answer)" @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                        </div>
                    </div>

                    <div class="col-md-4"></div>

                    <!--Display person who answer question details using UserInfo.vue-->
                    <div class="col-md-4">
                        <user-info :model="answer" label="Answered"></user-info>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
//axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

import Vote from './Vote.vue';
import UserInfo from './UserInfo.vue';
import modification from '../mixins/modification.js';

export default {

    props: ['answer'],  //name of model answer more descriptive

    mixins: [modification],

    components: { Vote, UserInfo },

    data () {
        return {
            //editing: false, /*********defined in mixins********* */
            body: this.answer.body,
            bodyHtml: this.answer.body_html,
            id: this.answer.id,
            questionId: this.answer.question_id,
            beforeEditCache: null //holde the answer body here before editing to call it if hit cancel method
        }
    },

    methods: {

        /*********defined in mixins********* */
        // edit () {
        //     this.beforeEditCache = this.body; //holde the answer body here before editing to call it if hit cancel method
        //     this.editing = true;
        // },

        // cancel () {
        //     this.body = this.beforeEditCache;
        //     this.editing = false
        // },
        /*********defined in mixins********* */

        setEditCache () {
            this.beforeEditCache = this.body; //holde the answer body here before editing to call it if hit cancel method
        },

        restoreFromCache () {
            this.body = this.beforeEditCache;
        },


        /*********defined in mixins********* */
        /**
         * sending request using update with axios using patch method and first arg is URI (questions/{question}/answers/{answer}) from artisan route:list
         * second arg specifing data will be sent in object
         * no need to csrf token because it set with axios by default in bootstrap.js
         * for flash message we used https://github.com/arthurvasconcelos/vue-izitoast & https://izitoast.marcelodolza.com/ and install it by
         *  npm install vue-izitoast -D (development mood)
         */
        // update () {
        //     axios.patch( this.endpoint , {  //update route from php artisan route:list --name=answers
        //         body: this.body //update this answer body
        //     })
        //     .then(response => {
        //         this.editing = false;
        //         this.bodyHtml = response.data.body_html;
        //         this.$toast.success(response.data.message, "Success", {timeout: 3000});
        //     })//success
        //     .catch(error => {
        //         //console.log(error.response)
        //         this.$toast.error(error.response.data.message, "Error", {timeout: 3000});
        //     });//fail
        // },
        /*********defined in mixins********* */

        payload () {
            return {
                body: this.body
            }
        },


        /*********defined in mixins********* */
        // destroy () {
        //     this.$toast.question('Are you sure about that?', "Confirm", {
        //         timeout: 20000,
        //         close: false,
        //         overlay: true,
        //         displayMode: 'once',
        //         id: 'question',
        //         zindex: 999,
        //         title: 'Hey',
        //         position: 'center',
        //         buttons: [

        //             ['<button><b>YES</b></button>', (instance, toast) => {
        //                 //our code//
        //                 axios.delete(this.endpoint)
        //                     .then(response => {
        //                         // $(this.$el).fadeOut(500, () => {
        //                         //     this.$toast.success(response.data.message, "Success", {timeout: 3000});
        //                         // })
        //                         /**we will replace this jquery by custome event
        //                          * which by it that the child component (answer) can send data to the parent component (answers)
        //                          * event that will be sent to the parent by $emit('eventname')
        //                          * this event will be called in the child component answer that declared in answers or parent component
        //                          * NOTE(parent component send data to child component by custom proprties)
        //                         **/
        //                         this.$emit('deleted')

        //                     });
        //                 //end our code //
        //                 instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
        //             }, true],

        //             ['<button>NO</button>', function (instance, toast) {

        //                 instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

        //             }],
        //         ],
        //         onClosing: function(instance, toast, closedBy){
        //             console.info('Closing | closedBy: ' + closedBy);
        //         },
        //         onClosed: function(instance, toast, closedBy){
        //             console.info('Closed | closedBy: ' + closedBy);
        //         }
        //     });
        // }
        /*********defined in mixins********* */

        delete() {
            axios.delete(this.endpoint)
                .then(response => {
                    this.$toast.success(response.data.message, "Success", {timeout:2000});
                    this.$emit('deleted')
            });
        }
    },

    computed: {
        isInvalid () {
            return this.body.length < 10; //it will return true or false
        },

        endpoint () {
            return `/questions/${this.questionId}/answers/${this.id}` ; //responce in AnswersController
        }
    }
}
</script>>
