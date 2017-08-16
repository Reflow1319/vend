<template>
    <modal-content :save="save" title="messages.edit">
        <error-message ref="errorMessage" />
        <editor v-model="message.message" placeholder="messages.form.message" />
        <div class="media-grid">
            <file-attachment v-for="file in message.files" v-bind:key="file.id" :file="file" />
        </div>
        <uploader type="message" :compact="true" :uploaded="attachFile" :text="$t('uploader.attach')" />
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import ModalContent from '../common/ModalContent.vue'
    import Editor from '../common/Editor.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import Uploader from '../common/Uploader.vue'
    import FileAttachment from '../files/FileAttachment.vue'

    export default {
      computed: {
        ...mapGetters({
          message: 'message'
        })
      },
      components: {
        ModalContent,
        Editor,
        Uploader,
        ErrorMessage,
        FileAttachment
      },
      methods: {
        save () {
          this.$store.dispatch('saveMessage', {
            topicId: this.message.topic_id,
            message: this.message.message,
            files: this.message.files,
            urlParams: {topicId: this.message.topic_id}
          })
            .then(() => {
              this.$root.$emit('hideModal')
            })
            .catch(err => {
              this.$refs.errorMessage.set(err)
            })
        },
        attachFile (file) {
          if (!this.message.files) this.$set(this.message, 'files', [])
          this.message.files.push(file)
        }
      }
    }
</script>