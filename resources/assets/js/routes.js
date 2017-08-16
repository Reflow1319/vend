import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
  linkActiveClass: 'active',
  routes: [
    {
      path: '/',
      name: 'projects',
      component: require('./pages/Projects.vue')
    },
    {
      path: '/notifications',
      name: 'notifications',
      component: require('./pages/Notifications.vue')
    },
    {
      path: '/projects/archive',
      name: 'projects-archive',
      component: require('./pages/Projects.vue')
    },
    {
      path: '/files',
      name: 'files',
      component: require('./pages/Files.vue')
    },
    {
      path: '/projects/:id',
      name: 'project',
      component: require('./pages/Project.vue')
    },
    {
      path: '/users',
      name: 'users',
      component: require('./pages/Users.vue')
    },
    {
      path: '/logs',
      name: 'logs',
      component: require('./pages/Logs.vue')
    },
    {
      path: '/calendar',
      name: 'calendar',
      component: require('./pages/Calendar.vue')
    },
    {
      path: '/calendar/:id',
      name: 'event',
      component: require('./pages/Calendar.vue')
    },
    {
      path: '/topics',
      name: 'topics',
      component: require('./pages/Topics.vue')
    },
    {
      path: '/topics/:id',
      name: 'topic',
      component: require('./pages/Messages.vue')},
    {
      path: '/topics/:id/files',
      name: 'topic-files',
      component: require('./pages/TopicFiles.vue')
    },
    {
      path: '/topics/:id/events',
      name: 'topic-events',
      component: require('./pages/TopicEvents.vue')
    }
  ]
})
