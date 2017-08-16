<template>
  <card @click="showCard()" :data-id="card.id">
    <template slot="body">
      {{ card.title }}
      <div class="card-assigned">
        <img :src="card.assigned.image" class="avatar avatar-small" v-if="card.assigned">
      </div>
    </template>
    <template slot="footer">
            <span v-if="card.tasks.length === 0 && card.is_completed">
                <i class="icon icon-checked text-green"></i>
            </span>
      <span v-if="card.tasks.length">
                <i :class="taskIconClass"></i>
                {{ completedTaskCount }}/{{ card.tasks.length }}
            </span>
      <span v-if="card.files.length"><i class="icon-paper-clip"></i></span>
      <span v-if="card.comments.length"><i class="icon-bubbles"></i></span>
      <span v-if="card.logs.length" :class="{'log-card-active': logActive}">
                <i class="icon icon-clock"></i>
            </span>
      <span class="label label-primary label-sm" v-if="card.due_date">
                {{ monthDateFormat(card.due_date) }}
            </span>
    </template>
  </card>
</template>

<script>
  import { mapGetters } from 'vuex'
  import {monthDateFormat} from '../../utils'
  import _ from 'lodash'
  import http from '../../http'
  import Card from '../common/Card.vue'

  export default {
    props: ['card'],
    components: {
      Card
    },
    computed: {
      ...mapGetters({
        currentLog: 'currentLog'
      }),
      completedTaskCount () {
        return _.filter(this.card.tasks, {is_completed: true}).length
      },
      logActive () {
        return this.currentLog &&
          this.currentLog.is_running &&
          this.currentLog.card.id === this.card.id
      },
      taskIconClass () {
        return [
          'icon',
          {'text-green': this.card.is_completed},
          this.completedTaskCount === this.card.tasks.length || this.card.is_completed
            ? 'icon-checked'
            : 'icon-unchecked'
        ]
      }
    },
    methods: {
      monthDateFormat,
      showCard () {
        this.$store.commit('setCard', this.card)
        this.$root.$emit('showModal', require('../cards/CardDetail.vue'))
        http.put('notifications/read/card/' + this.card.id)
      }
    }
  }
</script>