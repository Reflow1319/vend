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
                <a @click="$store.dispatch('toggleFavorite',{type: 'topics', id: topic.id})">
                    <i class="icon icon-star"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['topic'],
        methods: {
            editTopic() {
                this.$root.$emit('showModal', 'topic-form')
            }
        }
    }
</script>
