<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <form class="card-body" v-if="editing" @submit.prevent="update">
                    <!--display the queston header-->
                    <div class="card-title">
                        <input type="text" class="form-control form-control-lg" v-model="title">
                    </div>

                    <hr>
                    <!--Display question content-->
                    <div class="media">
                        <!--Display question body-->
                        <div class="media-body">
                            <div class="form-group">
                                <m-editor :body="body">
                                    <textarea class="form-control" v-model="body" rows="10" required></textarea>
                                </m-editor>
                            </div>
                            <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                            <button class="btn btn-outline-secondary" type="button" @click="cancel">Cancel</button>
                        </div>
                    </div>

                </form>

                <div class="card-body" v-else>
                    <!--display the queston header-->
                    <div class="card-title">
                        <div class="d-flex bd-highlight">
                            <h2 class="mr-auto bd-highlight">{{title}}</h2>
                            <div class="ml-auto bd-highlight q-buttons">
                                <a href="/questions" class="btn btn-outline-secondary">Back to all Questions</a>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!--Display question content-->
                    <div class="media">
                        <vote :model="question" name="question"></vote>

                        <!--Display question body-->
                        <div class="media-body">
                            <!--Display question body-->
                            <div v-html="bodyHtml"></div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="ml-auto bd-highlight q-buttons">
                                        <a v-if="authorize('modify', question)"  @click.prevent="edit" class="btn btn-outline-info btn-sm mr-2">Edit</a>
                                        <button v-if="authorize('deleteQuestion', question)" @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                                </div>

                                <div class="col-md-4"></div>

                                <div class="col-md-4">
                                    <!--Display person who ask question details using UserInfo.vue-->
                                    <user-info :model="question" label="Asked"></user-info>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>
import Vote from './Vote.vue';
import UserInfo from './UserInfo.vue';
import MEditor from './MEditor.vue';
import modification from '../mixins/modification.js';

    export default {
        props: ['question'],

        mixins: [modification],

        components: { Vote, UserInfo, MEditor },

        data() {
            return {
                title: this.question.title,
                body: this.question.body,
                bodyHtml: this.question.body_html,
                //editing: false, //to make the form is invisible by default /*********defined in mixins********* */
                id: this.question.id,
                beforeEditCache: {}
            }
        },

        computed: {
            isInvalid () {
                return this.body.lenght < 10 || this.title.length < 10;
            },

            endpoint () {
                return `/questions/${this.id}`;
            }
        },

        methods: {
            /*********defined in mixins********* */
            // edit () {
            //     this.beforeEditCache = {
            //         body: this.body,
            //         title: this.title
            //     };

            //     this.editing = true
            // },

            // cancel () {
            //     this.body = this.beforeEditCache.body;
            //     this.title = this.beforeEditCache.title;
            //     this.editing = false;
            // },
            /*********defined in mixins********* */

            setEditCache () {
                this.beforeEditCache = {
                    body: this.body,
                    title: this.title
                };
            },

            restoreFromCache () {
                this.body = this.beforeEditCache.body;
                this.title = this.beforeEditCache.title;
            },

            /*********defined in mixins********* */
            // update () {
            //     //we will use put instead of patch because wil update the title and body of the question
            //     axios.put(this.endpoint, {
            //         body: this.body,
            //         title: this.title
            //     })
            //     //server side validation error message in flash message
            //     .catch(({response}) => {
            //         this.$toast.error(response.data.message, "Error", {timeout: 3000});
            //     })
            //     //when succeed we need to replace the local bodyHtml with bodyHtml that come frome ajax response
            //     .then(({data}) => {
            //         this.bodyHtml = data.body_html;
            //         this.$toast.success(data.message, "Success", {timeout: 3000});
            //         this.editing = false;
            //     })
            // },
            /*********defined in mixins********* */

            payload () {
                return {
                    body: this.body,
                    title: this.title
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
            //                     .then(({data}) => {
            //                         this.$toast.success(data.message, "Success", {timeout:2000});
            //                     });
            //                     //redirect to questions page using javascript settimeout()
            //                     setTimeout(() => {
            //                         window.location.href = "/questions";
            //                     }, 3000);
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

            delete () {
                axios.delete(this.endpoint)
                    .then(({data}) => {
                        this.$toast.success(data.message, "Success", {timeout:2000});
                    });
                    //redirect to questions page using javascript settimeout()
                    setTimeout(() => {
                        window.location.href = "/questions";
                    }, 3000);
            }
        }
    }
</script>
