import Resource from './resource'
import http from '../http'

export default new Resource({
  store: 'user',
  additional: {
    state: {
      currentUser: {}
    },
    getters: {
      unReadNotifications: state => [],
      currentUser: state => state.currentUser,
      isAdmin: state => {
        return state.currentUser.role === 'admin'
      },
      isEditor: state => {
        return state.currentUser.role === 'admin' || state.currentUser.role === 'editor'
      }
    },
    mutations: {
      setCurrentUser (state, currentUser) {
        state.currentUser = currentUser
      }
    },
    actions: {
      getCurrentUser ({commit}) {
        http.get('users/current').then(res => {
          commit('setCurrentUser', res.data)
          if (res.data.logs) {
            const runningLog = _.find(res.data.logs, {is_running: 1})
            if (runningLog) {
              commit('setCurrentLog', runningLog)
            } else {
              commit('setCurrentLog', res.data.logs[0])
            }
          }
        })
      },
      toggleFavorite ({commit}, {type, id}) {
        http.post(type + '/' + id + '/favorite').then(res => {
          commit('setFavorite', {favorite: res.data, type: type, id: id})
        })
      }
    }
  }
})
