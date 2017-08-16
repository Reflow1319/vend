<template>
    <div class="media" @click="show()" :class="{'cursor-pointer': clicked}">
        <div class="media-left">
            <div class="media-stamp" :class="{'media-stamp-warn': highlight}">
                <b>{{ eventFormat(event.start).day }}</b>
                {{ eventFormat(event.start).month }}
            </div>
        </div>
        <div class="media-body">
            <div><b><span v-if="prefix">{{ prefix }}:</span> {{ event.title }}</b></div>
            <div class="text-muted small">{{ dateFormat(event.start, true) }}</div>
            <div class="text-muted small" v-if="event.end">{{ $t('events.end') }}: {{ dateFormat(event.end) }}</div>
            <div class="text-muted small" v-if="event.location">{{ $t('events.location') }}: {{ event.location }}</div>
        </div>
        <div v-if="event.type == 'event'" class="media-right">
            <a @click="destroy(event)"><i class="icon-delete"></i></a>
            <a @click="edit(event)"><i class="icon-pencil"></i></a>
        </div>
    </div>
</template>


<script>
    import {eventFormat, dateFormat} from '../../utils'

    import EventForm from './EventForm.vue'

    export default {
      props: ['event', 'highlight', 'clicked', 'prefix'],
      methods: {
        eventFormat,
        dateFormat,
        show () {
          if (this.clicked) this.clicked()
        },
        edit (event) {
          this.$store.commit('setEvent', event)
          this.$root.$emit('showModal', EventForm)
        },
        destroy (event) {
          this.$store.dispatch('deleteEvent', event)
        }
      }
    }
</script>