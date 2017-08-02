import {makeResource} from './resource'

export default makeResource('event', 'events', {
    getters: {
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
    }
})