<template>
    <div>
        <title-bar>
            <template slot="left">{{ $t('topics.index') }}</template>
            <template slot="right">
                <v-button @click="createTopic()" type="primary">{{ $t('topics.create') }}</v-button>
            </template>
        </title-bar>

        <div class="container">
            <loader ref="loader"></loader>

            <div v-if="empty" class="no-record">{{ $t('topics.empty') }}</div>

            <div v-if="loaded">
                <div v-if="unreadTopics.length">
                    <h4>{{ $t('topics.unread') }}</h4>
                    <div class="wbox">
                        <table class="table">
                            <tr v-for="topic in unreadTopics"
                                @click="$router.push({name:'topic', params: {id: topic.id}})"
                                class="cursor-pointer">
                                <td width="25%">
                                    <b>{{ topic.title }}</b>
                                </td>
                                <td width="2%">
                                    <div v-if="topic.latest_message">
                                        <img :src="topic.latest_message.user.image" alt="" class="avatar">
                                    </div>
                                </td>
                                <td width="83%">
                                    <div v-if="topic.latest_message" class="text-more small">
                                        <b>{{ topic.latest_message.user.name }}</b>
                                        <span class="text-muted">{{ fromNow(topic.latest_message.created_at) }}</span>
                                        <div>{{ topic.latest_message.excerpt }}</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div v-if="readTopics.length">
                    <h4>{{ $t('topics.read') }}</h4>
                    <div class="wbox">
                        <table class="table">
                            <tr v-for="topic in readTopics"
                                @click.prevent="$router.push({name:'topic', params: {id: topic.id}})"
                                class="cursor-pointer">
                                <td width="25%">
                                    <b>{{ topic.title }}</b>
                                </td>
                                <td width="2%">
                                    <div v-if="topic.latest_message">
                                        <img :src="topic.latest_message.user.image" alt="" class="avatar">
                                    </div>
                                </td>
                                <td width="83%">
                                    <div v-if="topic.latest_message" class="text-more small">
                                        <b>{{ topic.latest_message.user.name }}</b>
                                        <span class="text-muted">{{ fromNow(topic.latest_message.created_at) }}</span>
                                        <div>{{ topic.latest_message.excerpt }}</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {fromNow} from '../utils'
    import Loader from '../components/common/Loader.vue'
    import TopicForm from '../components/topics/TopicForm.vue'
    import TitleBar from '../components/common/TitleBar.vue'
    import VButton from '../components/common/Button.vue'

    export default {
      components: {
        Loader,
        VButton,
        TitleBar
      },
      computed: {
        ...mapGetters({
          topics: 'topics'
        }),
        readTopics () {
          return this.topics.filter(t => {
            return !t.unread
          })
        },
        unreadTopics () {
          return this.topics.filter(t => {
            return t.unread
          })
        }
      },
      data () {
        return {
          loaded: false,
          empty: false
        }
      },
      mounted () {
        this.$refs.loader.start()
        this.$store.dispatch('getTopics').then(() => {
          this.$refs.loader.stop()
          this.loaded = true
          if (this.topics.length === 0) {
            this.empty = true
          }
        })
      },
      methods: {
        fromNow,
        createTopic () {
          this.$root.$emit('showModal', TopicForm)
          this.$store.commit('setTopic', {users: []})
        }
      }
    }
</script>