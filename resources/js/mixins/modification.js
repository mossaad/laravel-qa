import Vote from '../components/Vote.vue';
import UserInfo from '../components/UserInfo.vue';
import MEditor from '../components/MEditor.vue';
import highlight from './highlight';

export default {

    mixins: [highlight],

    components: { Vote, UserInfo, MEditor },

    data () {
        return {
            editing: false,
        }
    },

    methods: {
        edit () {
            this.setEditCache();
            this.editing = true
        },

        cancel () {
            this.restoreFromCache();
            this.editing = false;
        },

        //define methods as empty methods like this and when call mixins we can override this methods as you like
        setEditCache() {},
        restoreFromCache() {},

        update () {
            axios.put(this.endpoint, this.payload())
            //server side validation error message in flash message
            .catch(({response}) => {
                this.$toast.error(response.data.message, "Error", {timeout: 3000});
            })
            //when succeed we need to replace the local bodyHtml with bodyHtml that come frome ajax response
            .then(({data}) => {
                this.bodyHtml = data.body_html;
                this.$toast.success(data.message, "Success", {timeout: 3000});
                this.editing = false;
            })
            .then(() => this.highlight()); //from highlight.js mixins
        },

        payload() {},

        destroy () {
            this.$toast.question('Are you sure about that?', "Confirm", {
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'Hey',
                position: 'center',
                buttons: [

                    ['<button><b>YES</b></button>', (instance, toast) => {
                        //our code//
                        this.delete();
                        //end our code //
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }, true],

                    ['<button>NO</button>', function (instance, toast) {

                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                    }],
                ],
                onClosing: function(instance, toast, closedBy){
                    console.info('Closing | closedBy: ' + closedBy);
                },
                onClosed: function(instance, toast, closedBy){
                    console.info('Closed | closedBy: ' + closedBy);
                }
            });
        },

        delete() {},
    }
}
