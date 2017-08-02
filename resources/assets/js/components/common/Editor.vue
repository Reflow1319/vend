<template>
    <div class="form-group">
        <div id="editor" class="form-control">
            <p v-html="content"></p>
        </div>
    </div>
</template>

<script>
    import Quill from 'quill'

    export default {
        props: {
            content: {
                type: String,
                default: ''
            },
            placeholder: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                editor: null
            }
        },
        mounted() {
            const editorEl = this.$el.querySelectorAll('#editor')[0];
            this.editor = new Quill(editorEl, {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'link', 'code-block'],
                    ]
                },
                placeholder: this.placeholder,
                theme: 'snow'
            })
        },
        methods: {
            getContent() {
                return this.editor.root.innerHTML.replace("<p><br></p>", "")
            },
            setContent(content) {
                this.editor.setContents(content || '')
            }
        }
    }
</script>