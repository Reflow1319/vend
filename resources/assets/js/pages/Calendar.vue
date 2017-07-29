<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">
                {{ $t('events.index') }}
            </div>
            <div class="title-bar-right">
                <nav class="title-bar-nav">
                    <a :href="'/calendar/' + currentUser.event_url + '/calendar.ics'" target="_blank">{{ $t('events.ics') }}</a>
                </nav>
                <a @click="create()" class="btn btn-primary title-bar-btn">{{ $t('events.create') }}</a>
            </div>
        </div>
        <div class="container">
            <loader ref="loader"></loader>
            <div v-for="(events, index) in groups" v-if="loaded">
                <h4>{{ getDate(index) }}</h4>
                <div class="wbox">
                    <event-attachment
                            v-for="event in events"
                            v-bind:key="event.id"
                            :clicked="eventClicked(event)"
                            :event="event"
                            :prefix="$t('events.prefix.' + event.type)"
                            :highlight="event.type == 'project'">
                    </event-attachment>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../components/common/Loader.vue'
    import EventAttachment from '../components/events/EventAttachment.vue'
    import {mapGetters} from 'vuex'
    import moment from 'moment'

    export default {
        components: {
            Loader,
            EventAttachment
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser',
                groups: 'eventsGrouped'
            })
        },
        mounted() {
            this.fetchEvents()
        },
        data() {
            return {
                loaded: false
            }
        },
        methods: {
            eventClicked(e) {
                if (e.type === 'project') {
                    return () => {
                        this.$router.push({name: 'project', params: {id: e.id}})
                    }
                }

                if (e.type === 'card') {
                    return () => {
                        this.$root.$emit('showModal', 'show-card', this.$store.dispatch('getCard', {id: e.id, project_id: e.meta.project_id}))
                    }
                }
            },
            getDate(date) {
                return moment(date, 'YYYY-MM').format('MMMM, YYYY')
            },
            create() {
                this.emit('showModal', 'event-form')
            },
            fetchEvents() {
                this.$refs.loader.start()
                this.$store.dispatch('getEvents').then(() => {
                    this.$forceUpdate()
                    this.$refs.loader.stop()
                    this.loaded = true
                })
            }
        }
    }
</script>