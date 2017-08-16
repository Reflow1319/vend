<template>
    <media :title="message.user.name" :meta="fromNow(message.created_at)">
        <avatar :image="message.user.image" slot="left" />
        <template slot="body">
            <div v-html="message.message"></div>
            <div class="media-grid">
                <file-attachment v-for="file in message.files" v-bind:key="file.id" :file="file"></file-attachment>
            </div>
        </template>
        <template slot="actions">
            <a @click="editMessage(message)">{{ $t('common.edit') }}</a>
            <a @click="deleteMessage(message)">{{ $t('common.delete') }}</a>
        </template>
    </media>
</template>

<script>
    import {fromNow} from '../../utils'
    import Dropdown from '../common/Dropdown.vue'
    import FileAttachment from '../files/FileAttachment.vue'
    import Media from '../common/Media'
    import Avatar from '../common/Avatar'

    export default {
      props: ['message'],
      components: {
        Dropdown,
        Avatar,
        Media,
        FileAttachment
      },
      methods: {
        fromNow,
        editMessage (message) {
          this.$store.commit('setMessage', message)
          this.$root.$emit('showModal', require('./MessageEdit.vue'))
        },
        deleteMessage (message) {
          this.$store.dispatch('deleteMessage', {id: message.id, urlParams: {topicId: message.topic_id}})
        }
      }
    }
</script>
