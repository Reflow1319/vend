<template>
    <div>
        <topic-header :topic="topic"></topic-header>

        <div class="container">

            <message-form></message-form>

            <div class="wbox">
                <message-item :message="message" v-for="message in messages" v-bind:key="message.id"></message-item>
                <loader ref="loader"></loader>
                <div class="no-record" v-if="empty && ! isLoading()">
                    {{ $t('messages.empty') }}
                </div>
            </div>

            <load-more :options="meta" @loaded="addData"></load-more>

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
        data() {
            return {
                empty: false,
                meta: {}
            }
        },
        mounted() {
            this.fetch()
        },
        methods: {
            addData(data) {
                this.meta = data
                this.$store.commit('setMessages', this.messages.concat(data.data))
            },
            fetch() {
                const topicId = this.$route.params.id
                this.$refs.loader.start()
                this.$store.commit('setTopic', {})
                this.$store.commit('setMessages', [])
                this.$store.dispatch('getTopic', {id: topicId}).then(() => {
                    this.$store.dispatch('getMessages', {urlParams: {topicId: topicId}}).then(data => {
                        this.meta = data
                        this.$refs.loader.stop()
                    })
                })
            },
            isLoading() {
                return this.$refs.loader.loading;
            },
            setEmpty() {
                this.empty = this.messages.length === 0;
            }
        },
        watch: {
            '$route': 'fetch',
            'messages': 'setEmpty'
        }
    }
</script>
