<template>
    <div class="title-bar container-fluid" v-if="topic.id">
        <div class="title-bar-title">
            <router-link to="/topics">{{ $t('topics.index') }}</router-link>
            <i class="icon-arrow-right title-bar-arrow"></i>
            {{ topic.title }}
        </div>
        <div class="title-bar-right">
            <nav class="title-bar-tabs">
                <router-link :to="{name: 'topic', params: {id: topic.id}}" exact>{{ $t('topics.messages') }}</router-link>
                <router-link :to="{name: 'topic-files', params: {id: topic.id }}">{{ $t('topics.files') }}</router-link>
            </nav>
            <div class="title-bar-nav">
                <a @click="editTopic()">
                    <i class="icon icon-pencil"></i>
                </a>
                <favorite-button type="topics" :id="topic.id"></favorite-button>
            </div>
        </div>
    </div>
</template>

<script>
    import FavoriteButton from '../common/FavoriteButton.vue'

    export default {
        props: ['topic'],
        components: {
            FavoriteButton
        },
        methods: {
            editTopic() {
                this.$root.$emit('showModal', 'topic-form')
            }
        }
    }
</script>
