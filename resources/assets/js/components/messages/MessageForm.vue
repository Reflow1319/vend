<template>
    <div class="mb-md">
        <form @submit.prevent="save()" class="message-form">

            <error-message ref="errorMessage"></error-message>

            <editor ref="editor" :content="message.message" :placeholder="$t('messages.form.message')"></editor>
            <div class="media-grid">
                <file-attachment v-for="file in message.files" v-bind:key="file" :file="file"></file-attachment>
            </div>

            <uploader type="message" :compact="true" :uploaded="attachFile" :text="$t('uploader.attach')"></uploader>

            <button class="btn btn-primary pull-right">{{ $t('common.save') }}</button>

        </form>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Uploader from '../files/Uploader.vue'
    import Editor from '../common/Editor.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import FileAttachment from '../files/FileAttachment.vue'

    export default {
        components: {
            Uploader,
            ErrorMessage,
            FileAttachment,
            Editor
        },
        computed: {
            ...mapGetters({
                message: 'message'
            })
        },
        methods: {
            save() {
                this.message.message = this.$refs.editor.getContent()

                this.$store.dispatch('saveMessage', {
                    topicId: this.$route.params.id,
                    message: this.message
                })
                    .then(() => {
                        this.$refs.editor.setContent('')
                        this.$store.commit('setMessage', {
                            files: []
                        })
                    })
                    .catch(err => {
                        this.setErrors(err)
                    })
            },
            attachFile(file) {
                this.message.files.push(file)
            }
        }
    }
</script>