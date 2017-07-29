export default {
    state: {
        event: {},
        events: []
    },
    getters: {
        event: state => state.event,
        events: state => state.events,
        eventsGrouped: state => {
            const groups = {}
            state.events = _.sortBy(state.events, 'start')
            state.events.forEach(e => {
                let date = new Date(e.start)
                e.type = e.type || 'event'
                if (!groups[date.getFullYear() + '-' + (date.getMonth() + 1)]) {
                    groups[date.getFullYear() + '-' + (date.getMonth() + 1)] = []
                }
                groups[date.getFullYear() + '-' + (date.getMonth() + 1)].push(e)
            })
            return groups
        }
    },
    mutations: {
        setEvent(state, event) {
            state.event = event
        },
        setEvents(state, events){
            state.events = events
        },
        createEvent(state, event) {
            state.events.push(event)
        },
        updateEvent(state, event) {
            state.event = event
            state.events = state.events.map(e => {
                if (e.id === event.id && e.type === 'event') return event
                return e;
            })
        },
        deleteEvent(state, event) {
            state.events = _.reject(state.events, {id: event.id, type: 'event'})
        }
    },
    actions: {
        getEvents({commit}) {
            return axios.get('events').then(res => {
                commit('setEvents', res.data)
            })
        },
        editEvent({commit}, event) {
            commit('setEvent', event)
            return Promise.resolve()
        },
        saveEvent({commit}, event) {
            if (event.id) {
                return axios.put('events/' + event.id, event)
                    .then(res => {
                        commit('updateEvent', res.data)
                    })
            }
            return axios.post('events', event)
                .then(res => {
                    commit('createEvent', res.data)
                })
        },
        deleteEvent({commit}, event) {
            axios.delete('events/' + event.id).then(() => {
                commit('deleteEvent', event)
            })
        }
    }

}