<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">
                {{ $t('notifications.index') }}
            </div>
            <div class="title-bar-right">
                <a href="#" class="btn btn-primary title-bar-btn">{{ $t('notifications.clear') }}</a>
            </div>
        </div>
        <div class="container">
            <div class="wbox">
                <div class="notifications">
                    <div v-for="notification in notifications" v-bind:key="notification.id"
                         class="media cursor-pointer" @click="showNotification(notification)">
                        <div class="media-left">
                            <div class="notification-dot" :class="{'unread' : notification.read_at == null }"></div>
                        </div>
                        <div class="media-left">
                            <img :src="notification.actor.image" alt="" class="avatar">
                        </div>
                        <div class="media-body">
                            <div>{{ getNotificationText(notification) }}</div>
                            <span class="text-muted">{{ fromNow(notification.created_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import moment from 'moment'

    export default {
        data() {
            return {
                interval: null
            }
        },
        computed: {
            ...mapGetters({
                notifications: 'notifications'
            })
        },
        methods: {
            showNotification(notification) {
                notification.read_at = moment().format('YYYY-MM-DD HH:ii:ss')
                if (notification.type === 'card_updated' || notification.type === 'card_created' || notification.type === 'comment_created') {
                    this.$root.$emit('showModal', 'show-card', this.$store.dispatch('getCard', notification.related))
                }
                if (notification.type === 'message_created') {
                    this.$router.push({name: 'topic', params: {id: notification.related.id}})
                }
            },
            getNotificationText(notification) {
                let data = {
                    actor: notification.actor.name
                }
                switch (notification.type) {
                    case 'card_updated':
                        data.title = notification.related.title
                        break
                    case 'message_created':
                        data.title = notification.related.title
                        break
                    case 'comment_created':
                        data.title = notification.related.title
                        break
                }

                return this.$t('notifications.messages.' + notification.type, data)
            },
            markAsRead() {
                axios.get('notifications/read').then(() => {
                })
            },
            clear() {
                axios.get('notifications/clear').then(() => {
                    this.$store.commit('setNotifications', [])
                })
            }
        }
    }
</script>
