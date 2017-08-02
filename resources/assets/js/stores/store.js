import Vue from 'vue'
import Vuex from 'vuex'
import cards from './cards'
import users from './users'
import events from './events'
import logs from './logs'
import {makeResource} from './resource'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        cards,
        topics: makeResource('topic'),
        topicFiles: makeResource('topic_file', 'topics/{topicId}/files'),
        messages: makeResource('message', 'topics/{topicId}/messages'),
        notifications: makeResource('notification'),
        events,
        favorites: makeResource('favorite'),
        users,
        logs,
        projects: makeResource('project'),
    }
})