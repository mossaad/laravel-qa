<template>
    <!--display votes controles-->
    <div class="d-flex flex-column vote-controles">

        <!--display votes-up-->
        <a @click.prevent="voteUp" :title="title('up')"
            class="vote-up" :class="classes">
            <i class="fas fa-caret-up fa-3x"></i>
        </a>

        <!--display votes_count-->
        <span class="vote-count">{{count}}</span>

        <!--display votes-down-->
        <a @click.prevent="voteDown" :title="title('down')"
            class="vote-down" :class="classes">
            <i class="fas fa-caret-down fa-3x"></i>
        </a>

        <!--Mark this question as favorite-->
        <favorite v-if="name=='question'" :question="model"></favorite>
        <!--Mark the answer as best answert-->
        <accept v-else :answer="model"></accept>

    </div>
</template>

<script>
import Favorite from './Favorite.vue';
import Accept from './Accept.vue';

    export default {
        props: ['name', 'model'],

        computed: {
            classes () {
                return this.signedIn ? '' : 'off';
            },

            endpoint () {
                return `/${this.name}s/${this.id}/vote`;
            }
        },

        components: {
            Favorite,
            Accept
        },

        data () {
            return {
                count: this.model.votes_count || 0 ,
                id: this.model.id
            }
        },

        methods: {
            title(voteType) {
                let titles = {
                    up: `This ${this.name} is useful`,
                    down: `This ${this.name} is not useful`
                }
                return titles[voteType];
            },

            voteUp () {
                this._vote(1)
            },

            voteDown () {
                this._vote(-1)
            },

            _vote (vote) {
                if (! this.signedIn) {
                    this.$toast.warning(`Please login to vote the ${this.name}`, "Warning", {
                        timeout: 3000,
                        position: 'bottomLeft'
                    });

                    return;
                }
                axios.post(this.endpoint, {
                    vote: vote
                })
                .then(res => {
                    this.$toast.success(res.data.message, "Success", {
                        timeout: 3000,
                        position: 'bottomLeft'
                    });

                    this.count = res.data.votesCount; //votes count defined in VotesAnswerController and VotesQuestionController and User model
                })
            }
        }
    }
</script>
