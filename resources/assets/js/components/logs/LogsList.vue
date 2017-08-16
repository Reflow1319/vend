<template>
  <div>
    <div v-if="add" class="form-inline">
      <div class="form-group">
        <input type="text" v-model="log.length" class="form-control" :placeholder="$t('logs.length')">
      </div>
      <datepicker v-model="log.date" :placeholder="$t('logs.date')"/>
      <div class="form-group form-group-btn">
        <a @click="saveLog()" class="btn btn-primary">{{ $t('common.save') }}</a>
      </div>
    </div>
    <table class="table">
      <tr v-for="log in logs">
        <td width="5%">
          <div class="label label-primary">{{ elapsedFormat(log.length) }}</div>
        </td>
        <td width="35%" v-if="showCard">
          <b>{{ log.card.title }}</b><br>
          <span class="text-muted">{{ dateFormat(log.created_at) }}</span>
        </td>
        <td width="2%"><img :src="log.user.image" alt="" class="avatar" v-if="log.user"></td>
        <td :width="showCard ? '58%' : '82%'">
          <a @click="deleteLog(log)" class="pull-right" v-if="log.user.id == currentUser.id">
            <i class="icon-delete"></i>
          </a>
          <div v-if="log.user">
            {{ log.user.name }}<br>
            <span class="text-muted">{{ log.user.email }}</span>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import { elapsedFormat, dateFormat, toSeconds } from '../../utils'
  import http from '../../http'
  import Datepicker from '../common/Datepicker.vue'

  export default {
    props: ['logs', 'small', 'add', 'card', 'show-card'],
    components: {
      Datepicker
    },
    data () {
      return {
        log: {}
      }
    },
    computed: {
      ...mapGetters({
        currentUser: 'currentUser'
      }),
      innerLogs () {
        return this.logs
      }
    },
    methods: {
      elapsedFormat,
      dateFormat,
      toSeconds,
      saveLog () {
        this.log.card_id = this.card.id

        const log = Object.assign({}, this.log, {length: this.toSeconds(this.log.length)})

        http.post('logs', log).then(res => {
          this.log = {}
          this.innerLogs.push(res.data)
          this.$emit('changed', this.innerLogs)
        })
      },
      deleteLog (log) {
        http.delete('logs/' + log.id).then(() => {
          let logIndex = _.findIndex(this.logs, {id: log.id})
          this.innerLogs.splice(logIndex, 1)
          this.$emit('changed', this.innerLogs)
        })
      }
    }
  }
</script>