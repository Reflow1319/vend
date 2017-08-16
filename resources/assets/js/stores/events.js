import Resource from './resource'
import _ from 'lodash'

export default new Resource({
  store: 'event',
  additional: {
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
  }
})
