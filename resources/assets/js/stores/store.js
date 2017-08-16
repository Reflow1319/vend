import Vue from 'vue'
import Vuex from 'vuex'
import users from './users'
import events from './events'
import logs from './logs'
import Resource from './resource'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    cards: new Resource({
      store: 'card',
      baseUrl: 'projects/{projectId}/cards',
      options: {
        addType: 'prepend'
      }
    }),
    topics: new Resource({
      store: 'topic'
    }),
    topicFiles: new Resource({
      store: 'topic_file',
      baseUrl: 'topics/{topicId}/files'
    }),
    messages: new Resource({
      store: 'message',
      baseUrl: 'topics/{topicId}/messages',
      options: {
        paginated: true
      }
    }),
    notifications: new Resource({
      store: 'notification',
      options: {
        paginated: true
      }
    }),
    events,
    favorites: new Resource({
      store: 'favorite'
    }),
    users,
    logs,
    projects: new Resource({
      store: 'project'
    })
  }
})
