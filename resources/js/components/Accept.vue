<template>
    <div>
        <!--Mark the answer as best answert if the user is owner of the question-->
        <a v-if="canAccept" title="Mark this answer as best answer"
            :class="classes" @click="create">
            <i class="fas fa-check fa-2x"></i>
        </a>
        <!--Determine that the owner of the question can only accept the answer as best answer-->
        <a v-if="accepted" title="The owner of this question accept this answer as best answer" :class="classes">
            <i class="fas fa-check fa-2x"></i>
        </a>


    </div>
</template>

<script>

    import EventBus from '../event-bus';

    export default {
        props: ['answer'],

        data () {
            return {
                isBest:  this.answer.is_best,
                id: this.answer.id
            }
        },

        /**
         * the best way to run the accepted event by created() hoak
         *  because we will be able to access directive data imn events and templateds and virtual dom has not been yet mounted or rendered
        **/
        created () {
            EventBus.$on('accepted', id => {
                /**
                 * inside this we simply match the id that we get from the event payload with the current answer id
                 * the result is bolean value and assign it to isBest data property
                 **/
                this.isBest = (id == this.id);
            })
        },

        methods: {
            create () {
                axios.post(`/answers/${this.id}/accept`) // response in AcceptAnswerController
                    .then(res => {
                        this.$toast.success(res.data.message, "Success", {
                            timeout: 3000,
                            position: 'bottomLeft'
                        });

                        this.isBest = true;

                        EventBus.$emit('accepted', this.id);
                    })
            }
        },

        computed: {
            canAccept () {
                return this.authorize('accept', this.answer); //authorize function created in authorize.js
            },

            accepted () {
                return ! this.canAccept && this.isBest;
            },

            classes () {
                return [
                    'mt-2',
                    this.isBest ? 'vote-accepted' : '',
                ]
            }

        }
    }
</script>>


