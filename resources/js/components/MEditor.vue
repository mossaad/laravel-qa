<template>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#write">Write</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#preview">Preview</a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            <div class="tab-pane active" id="write">
                <!--slot tag is avue component used instead of textarea to emit events to send data from child component to parent component-->
                <slot></slot>
            </div>
            <div class="tab-pane" v-html="preview" id="preview"></div>
        </div>
    </div>
</template>

<script>
/**
 * markdown-it package
 * to preview what you want to publish look like befor publish it install pakage from https://www.npmjs.com/package/markdown-it
 * run at terminal -> npm i markdown-it -D (add -D to indicate that package dependency require for development)
 * to write javascript code in the textarea and preview it like that -> ```javascript #write code here ```
 *
 * autosize package
 * to keep the full size of textarea without scrollbar instal autosize package from https://github.com/jackmoore/autosize
 * run at terminal -> npm install autosize -D
 *
 * markdown-it-prism
 * integrate syntax highlight official site https://prismjs.com/
 * install code typing themes from https://www.npmjs.com/package/markdown-it-prism
 * run to teriminal-> npm i markdown-it-prism -D
 * copy the themes from node modules by -> copy('node_modules/prismjs/themes', 'public/css/prismj-themes') in webpack.mix.js and run npm watch
 * the folder copied in public css folder
 * import them you want it in MEditor -> import 'prismjs/themes/prism-okaidia.css';
 * and call this css file in app.blade.php -> <link href="{{ asset('css/prismjs-themes/prism-okaidia.css') }}" rel="stylesheet">
 *
 */
    import MarkdownIt from 'markdown-it';
    import prism from 'markdown-it-prism';
    import autosize from 'autosize';
    import 'prismjs/themes/prism-okaidia.css';

    const md = new MarkdownIt();
    md.use(prism);

    export default {
        props: ['body'],

        computed: {
            preview () {
                return md.render(this.body);
            }
        },

        //we can use for autosize watch
        // watch: {
        //     body: function () {
        //         autosize(document.querySelector('textarea'));
        //     }
        // },

        //or
        mounted () { // to get the full size as soon as we hit the edit button we use mounted () with updated()
            autosize(this.$el.querySelector('textarea'));
        },

        updated () {
            //autosize(document.querySelector('textarea')); javasceipt syntax or
            autosize(this.$el.querySelector('textarea')); //Vue syntax
        }
    }
</script>
