<template>
    <div>
        <title-bar :title="$t('notifications.index')">
            <template slot="right">
                <ui-button @click="markAsRead()" type="primary" :label="$t('notifications.markAsRead')" />
            </template>
        </title-bar>

        <div class="container">
            <div class="wbox">
                <div class="notifications">
                    <media v-for="notification in notifications" :key="notification.id" @click="showNotification(notification)">
                        <template slot="left">
                            <div class="notification-dot" :class="{'unread' : notification.read_at == null }"></div>
                            <img :src="notification.actor.image" alt="" class="avatar">
                        </template>
                        <template slot="body">
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
                        </template>
                    </media>
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
    import CardDetail from '../components/cards/CardDetail.vue'
    import EventDetail from '../components/events/EventDetail.vue'
    import MessageDetail from '../components/messages/MessageDetail.vue'
    import TitleBar from '../components/common/TitleBar.vue'
    import UiButton from '../components/common/Button.vue'
    import Media from '../components/common/Media.vue'

    export default {
        components: {
            LoadMore,
            Media,
            UiButton,
            TitleBar
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

                if (
                    notification.related_type === 'card'
                    || notification.related_type === 'comment'
                    || notification.related_type === 'task'
                ) {
                    this.showCard(notification, notification.related_type !== 'card')
                }

                if (notification.related_type === 'event') {
                    this.showEvent(notification)
                }

                if (notification.related_type === 'message') {
                    this.showMessage(notification)
                }
            },
            showEvent(notification) {
                this.$root.$emit(
                    'showModal',
                    EventDetail,
                    this.$store.dispatch('getEvent', {
                        id: notification.related_id
                    }).then(() => {
                        this.markAsRead(notification)
                    })
                )
            },
            showCard(notification, withCardId) {
                this.$root.$emit(
                    'showModal',
                    CardDetail,
                    this.$store.dispatch('getCard', {
                        id: withCardId ? notification.data.card_id : notification.related_id,
                        urlParams: {
                            projectId: notification.data.project_id
                        }
                    }).then(() => {
                        this.markAsRead(notification)
                    })
                )
            },
            showMessage(notification) {
                this.$root.$emit(
                    'showModal',
                    MessageDetail,
                    this.$store.dispatch('getMessage', {
                        id: notification.data.message_id,
                        urlParams: {
                            topicId: notification.data.topic_id
                        }
                    }).then(() => {
                        this.markAsRead(notification)
                    }).catch(err => this.emit('show'))
                )
            },
            getNotificationText(notification) {
                notification.data.actor = notification.actor.name
                const key = `${notification.related_type}_${notification.type}`
                return this.$t('notifications.messages.' + key, notification.data)
            },
            markAsRead(notification) {
                axios.put('notifications/read/' + notification.related_type + '/' + notification.related_id)
            }
        }
    }
</script>

<style lang="scss">
    @import "../../sass/variables";

    .notification-dot {
        width: 8px;
        height: 8px;
        margin: 5px 10px 0 0;
        background: rgba(255, 255, 255, .3);
        border-radius: 4px;
        display: inline-block;
        &.unread {
            background: $green-light;
        }
    }
    .notification-changes {
        font-size: 13px;
        background: lighten($light-gray, 3);
        padding: 5px;
        margin: 10px 0;
        b {
            min-width: 100px;
            display: inline-block;
        }
        i {
            opacity: .5;
            font-size: 12px;
        }
    }
</style>

<style lang="scss">
    @import "../../sass/variables";

    .notification-dot {
        width: 8px;
        height: 8px;
        margin: 5px 10px 0 0;
        background: rgba(255, 255, 255, .3);
        border-radius: 4px;
        display: inline-block;
        &.unread {
            background: $green-light;
        }
    }
    .notification-changes {
        font-size: 13px;
        background: lighten($light-gray, 3);
        padding: 5px;
        margin: 10px 0;
        b {
            min-width: 100px;
            display: inline-block;
        }
        i {
            opacity: .5;
            font-size: 12px;
        }
    }
</style>