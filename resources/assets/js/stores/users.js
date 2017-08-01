import {makeResource} from './resource'

export default makeResource('user', null, {
    state: {
        currentUser: {},
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
        setCurrentUser(state, currentUser) {
            state.currentUser = currentUser
        },
    },
    actions: {
        getCurrentUser({commit}) {
            axios.get('users/current').then(res => {
                commit('setCurrentUser', res.data)
                if(res.data.logs) {
                    const runningLog = _.find(res.data.logs, {is_running: 1})
                    if(runningLog) {
                        commit('setCurrentLog', runningLog)
                    }
                    else {
                        commit('setCurrentLog', res.data.logs[0])
                    }
                }

            })
        },
        toggleFavorite({commit}, {type, id}) {
            axios.post(type + '/' + id + '/favorite').then(res => {
                commit('setFavorite', {favorite: res.data, type: type, id: id})
            })
        }
    }
})