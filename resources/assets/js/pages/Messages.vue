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
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import TopicHeader from '../components/topics/TopicHeader.vue'
    import Loader from '../components/common/Loader.vue'
    import MessageItem from '../components/messages/MessageItem.vue'
    import MessageForm from '../components/messages/MessageForm.vue'

    export default {
        components: {
            TopicHeader,
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
                empty: false
            }
        },
        mounted() {
            this.fetch()
        },
        methods: {
            fetch() {
                this.$refs.loader.start()
                this.$store.commit('setTopic', {})
                this.$store.commit('setMessages', [])
                this.$store.dispatch('getTopic', {id: this.$route.params.id}).then(() => {
                    const url = 'topics/' + this.$route.params.id + '/messages'
                    this.$store.dispatch('getMessages', {_url: url}).then(() => {
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
