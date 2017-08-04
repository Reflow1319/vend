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
                const type = notification.related_type + '_' + notification.type

                notification.read_at = moment().format('YYYY-MM-DD HH:ii:ss')

                if (notification.related_type === 'card') {
                    this.showCard(notification)
                }

                if (notification.related_type === 'comment') {
                    this.showCard(notification)
                }

                if (notification.related_type === 'event') {
                    this.showEvent(notification)
                }

                if (notification.related_type === 'message') {
                    this.$router.push({name: 'topic', params: {id: notification.related.id}})
                }
            },
            showEvent(notification) {
                this.$root.$emit(
                    'showModal',
                    'event-detail',
                    this.$store.dispatch('getEvent', {
                        id: notification.related_id
                    }).then(() => {
                        axios.put('notifications/read/' + notification.related_type + '/' + notification.related_id)
                    })
                )
            },
            showCard(notification) {
                this.$root.$emit(
                    'showModal',
                    'show-card',
                    this.$store.dispatch('getCard', {
                        id: notification.related_id,
                        urlParams: {
                            projectId: notification.data.project_id
                        }
                    }).then(() => {
                        axios.put('notifications/read/' + notification.related_type + '/' + notification.related_id)
                    })
                )
            },
            getNotificationText(notification) {
                notification.data.actor = notification.actor.name
                const key = `${notification.related_type}_${notification.type}`
                return this.$t('notifications.messages.' + key, notification.data)
            },
            markAsRead() {
                axios.get('notifications/read')
            }
        }
    }
</script>
