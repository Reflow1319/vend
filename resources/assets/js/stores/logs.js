import Resource from './resource'
import http from '../http'

export default new Resource({
  store: 'log',
  additional: {
    state: {
      currentLog: null,
      timer: 0,
      interval: null
    },
    getters: {
      currentLog: state => state.currentLog,
      timer: state => state.timer
    },
    mutations: {
      setCurrentLog (state, currentLog) {
        if (currentLog) {
          state.currentLog = currentLog
          state.timer = currentLog.length
        }
        if (currentLog && currentLog.is_running) {
          state.interval = setInterval(() => {
            state.timer++
          }, 1000)
        } else {
          clearInterval(state.interval)
        }
      }
    },
    actions: {
      toggleTimer ({commit}, card) {
        return http.get(`logs/toggle/${card.id}`).then(res => {
          commit('setCurrentLog', res.data)
          return res.data
        })
      }
    }
  }
})
