<template>
    <title-bar v-if="topic.id">
        <template slot="left">
            <router-link to="/topics">{{ $t('topics.index') }}</router-link>
            <i class="icon-arrow-right title-bar-arrow"></i>
            {{ topic.title }}
        </template>
        <template slot="tabs">
            <router-link :to="`/topics/${topic.id}`" exact>{{ $t('topics.messages') }}</router-link>
            <router-link :to="`/topics/${topic.id}/files`">{{ $t('topics.files') }}</router-link>
        </template>
        <template slot="right">
            <a @click="editTopic()" class="btn"><i class="icon icon-pencil"></i></a>
            <favorite-button type="topics" :id="topic.id"></favorite-button>
        </template>
    </title-bar>
</template>

<script>
    import FavoriteButton from '../common/FavoriteButton.vue'
    import TopicForm from '../topics/TopicForm.vue'
    import TitleBar from '../common/TitleBar.vue'

    export default {
      props: ['topic'],
      components: {
        FavoriteButton,
        TitleBar
      },
      methods: {
        editTopic () {
          this.$root.$emit('showModal', TopicForm)
        }
      }
    }
</script>
