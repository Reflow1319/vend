<template>
    <div>
        <title-bar>
            <template slot="left">{{ $t('events.index') }}</template>
            <template slot="right">
                <v-button :href="`/calendar/${currentUser.event_url}/calendar.ics`" target="_blank">{{ $t('events.ics') }}</v-button>
                <v-button @click="create" type="primary">{{ $t('events.create') }}</v-button>
            </template>
        </title-bar>

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
    import {mapGetters} from 'vuex'
    import moment from 'moment'
    import Loader from '../components/common/Loader.vue'
    import VButton from '../components/common/Button.vue'
    import EventAttachment from '../components/events/EventAttachment.vue'
    import EventDetail from '../components/events/EventDetail.vue'
    import EventForm from '../components/events/EventForm.vue'
    import TitleBar from '../components/common/TitleBar.vue'
    import CardDetail from '../components/cards/CardDetail.vue'

    export default {
      components: {
        Loader,
        VButton,
        TitleBar,
        EventAttachment
      },
      computed: {
        ...mapGetters({
          currentUser: 'currentUser',
          groups: 'eventsGrouped'
        })
      },
      mounted () {
        this.fetchEvents()

        if (this.$route.params.id) {
          this.$root.$emit(
            'showModal',
            EventDetail,
            this.$store.dispatch('getEvent', {id: this.$route.params.id})
          )
        }
      },
      data () {
        return {
          loaded: false
        }
      },
      methods: {
        eventClicked (e) {
          if (e.type === 'project') {
            return () => {
              this.$router.push({
                name: 'project',
                params: {id: e.id}
              })
            }
          }

          if (e.type === 'event') {
            return () => {
              this.$root.$emit(
                'showModal',
                EventDetail,
                this.$store.dispatch('getEvent', {id: e.id})
              )
            }
          }

          if (e.type === 'card') {
            return () => {
              this.$root.$emit(
                'showModal',
                CardDetail,
                this.$store.dispatch('getCard', {
                  id: e.id,
                  urlParams: {projectId: e.meta.project_id}
                })
              )
            }
          }
        },
        getDate (date) {
          return moment(date, 'YYYY-MM').format('MMMM, YYYY')
        },
        create () {
          this.$store.commit('setEvent', {})
          this.$root.$emit('showModal', EventForm)
        },
        fetchEvents () {
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