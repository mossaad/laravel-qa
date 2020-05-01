<template>
        <!--Mark this question as favorite-->
        <a title="Click to mark as favorit question (click again to undo)"
            :class="classes" @click.prevent="toggle">
            <i class="fas fa-star fa-2x"></i>
            <!--display queston favorites_count-->
            <span class="favorite-count">{{count}}</span>
        </a>
</template>

<script>
    export default {
        props: ['question'],

        data () {
            return {
                isFavorited: this.question.is_favorited,
                count: this.question.favorites_count,
                //signedIn: false, //store the cuurent user info into javascript window global object in layouts/app.blade.php
                id: this.question.id,
            }
        },

        computed: {
            classes () {
                return [
                    'mt-2',
                    ! this.signedIn ? 'off' : (this.isFavorited ? 'favorited' : '')
                ];
            },

            endpoint () {
                return `/questions/${this.id}/favorites` ;
            },

            //Replacing this computed with authorize.js
            // signedIn () {
            //     return window.Auth.signedIn;
            // }
        },

        methods: {
            toggle () {
                if(! this.signedIn) {
                    this.$toast.warning("Please login to favorite this question", "Warning", {
                        timeout: 3000,
                        position: 'bottomLeft'
                    });
                    return;
                }
                this.isFavorited ? this.destroy() : this.create();
            },

            destroy () {
                axios.delete(this.endpoint) //responce in FavoriteQuestionController
                    .then(res => {
                        this.count--;
                        this.isFavorited = false;
                    });
            },

            create () {
                axios.post(this.endpoint) //responce in FavoriteQuestionControlle
                    .then(res => {
                        this.count++;
                        this.isFavorited = true;
                    });
            }
        }
    }
</script>
