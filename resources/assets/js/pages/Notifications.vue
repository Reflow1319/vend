<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">
                {{ $t('notifications.index') }}
            </div>
            <div class="title-bar-right">
                <a @click="markAsRead()" class="btn btn-primary title-bar-btn">{{ $t('notifications.markAsRead') }}</a>
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
                            <div v-html="getNotificationText(notification)"></div>
                            <div class="notification-changes" v-if="notification.data.changes">
                                <div v-for="change in notification.data.changes">
                                    <b>{{ $t('cards.form.' + camelProperty(change.property, true)) }}:</b>
                                    <span>{{ change.from }}</span>
                                    <i class="icon-arrow-right"></i>
                                    <span>{{ change.to }}</span>
                                </div>
                            </div>
                            <span class="text-muted">{{ fromNow(notification.created_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <load-more :options="meta" @loaded="addData"></load-more>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import moment from 'moment'
    import {camelize} from 'inflection'
    import LoadMore from '../components/common/LoadMore.vue'

    export default {
        components: {
            LoadMore
        },
        data() {
            return {
                interval: null,
                meta: {}
            }
        },
        computed: {
            ...mapGetters({
                notifications: 'notifications'
            })
        },
        mounted() {
            this.$store.dispatch('getNotifications').then(res => {
                this.meta = res
            })
        },
        methods: {
            addData(data) {
                this.meta = data
                this.$store.commit('setNotifications', this.notifications.concat(data.data))
            },
            camelProperty(prop) {
                return camelize(prop, true)
            },
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
                notification.data.actor = notification.actor.name
                const key = `${notification.related_type}_${notification.type}`
                return this.$t('notifications.messages.' + key, notification.data)
            },
            getNotificationChanges(notification) {
                return notification.changes
                    ? notification.changes
                    : null
            },
            markAsRead() {
                axios.get('notifications/read')
            }
        }
    }
</script>
