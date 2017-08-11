<template>
    <div class="media">
        <div class="media-left">
            <img :src="message.user.image" alt="" class="avatar">
        </div>
        <div class="media-body">
            <div class="media-heading">
                <b>{{ message.user.name }}</b>
                <span class="text-muted">{{ fromNow(message.created_at) }}</span>
            </div>
            <div v-html="message.message"></div>
            <div class="media-grid">
                <file-attachment v-for="file in message.files" v-bind:key="file.id" :file="file"></file-attachment>
            </div>
        </div>
        <div class="media-right">
            <dropdown :right="true">
                <i class="icon-dots" slot="toggle"></i>
                <ul class="dropdown-links" slot="content">
                    <li><a @click="editMessage(message)">{{ $t('common.edit') }}</a></li>
                    <li><a @click="deleteMessage(message)">{{ $t('common.delete') }}</a></li>
                </ul>
            </dropdown>
        </div>
    </div>
</template>

<script>
    import Dropdown from '../common/Dropdown.vue'
    import FileAttachment from '../files/FileAttachment.vue'
    import MessageEdit from './MessageEdit.vue'

    export default {
        props: ['message'],
        components: {
            Dropdown,
            FileAttachment,
        },
        methods: {
            editMessage(message) {
                this.$store.commit('setMessage', message)
                this.$root.$emit('showModal', MessageEdit)
            },
            deleteMessage(message) {
                this.$store.dispatch('deleteMessage', {id: message.id, urlParams: {topicId: message.topic_id}})
            }
        }
    }
</script>
