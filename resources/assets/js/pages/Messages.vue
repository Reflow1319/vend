<template>
    <div>
        <topic-header :topic="topic" />
        <div class="container">
            <message-form />
            <div class="wbox">
                <message-item :message="message" v-for="message in messages" v-bind:key="message.id" />
                <loader :loading="loading" />
                <div class="no-record" v-if="empty && !loading">
                    {{ $t('messages.empty') }}
                </div>
            </div>
            <load-more :options="meta" @loaded="addData" />
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import TopicHeader from '../components/topics/TopicHeader.vue'
    import Loader from '../components/common/Loader.vue'
    import MessageItem from '../components/messages/MessageItem.vue'
    import MessageForm from '../components/messages/MessageForm.vue'
    import LoadMore from '../components/common/LoadMore.vue'

    export default {
      components: {
        TopicHeader,
        LoadMore,
        MessageItem,
        MessageForm,
        Loader
      },
      computed: {
        ...mapGetters({
          topic: 'topic',
          messages: 'messages'
        })
      },
      data () {
        return {
          empty: false,
          loading: false,
          meta: {}
        }
      },
      mounted () {
        this.fetch()
      },
      methods: {
        addData (data) {
          this.meta = data
          this.$store.commit('setMessages', this.messages.concat(data.data))
        },
        fetch () {
          const topicId = this.$route.params.id
          this.loading = true
          this.$store.commit('setTopic', {})
          this.$store.commit('setMessages', [])
          this.$store.dispatch('getTopic', {id: topicId}).then(() => {
            this.$store.dispatch('getMessages', {urlParams: {topicId: topicId}}).then(data => {
              this.meta = data
              this.loading = false
            })
          })
        },
        setEmpty () {
          this.empty = this.messages.length === 0
        }
      },
      watch: {
        '$route': 'fetch',
        'messages': 'setEmpty'
      }
    }
</script>
