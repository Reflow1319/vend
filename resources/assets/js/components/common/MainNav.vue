<template>

  <sidebar background="#495891" :open="true">

    <template slot="heading">
      <a href="#" class="logo"></a>
      <dropdown :right="true">
        <img :src="currentUser.image" alt="" class="avatar" slot="toggle">
        <template slot="content">
          <div class="dropdown-header">
            <b>{{ currentUser.name }}</b><br>
            <span>{{ $t('users.roles.' + currentUser.role) }}</span>
          </div>
          <a href="/logout">{{ $t('auth.logout') }}</a>
        </template>
      </dropdown>
    </template>

    <nav-list :light="true">
      <nav-list-item to="projects" icon="stack" :label="$t('projects.index')"
                     :classes="{'active' : $route.name === 'project'}" :exact="true"/>
      <nav-list-item to="notifications" icon="flame" :badge="unread" :label="$t('notifications.index')"/>
      <nav-list-item to="topics" icon="bubbles" :label="$t('topics.index')"/>
      <nav-list-item to="users" icon="users" :label="$t('users.index')" v-if="isEditor"/>
      <nav-list-item to="calendar" icon="calendar" :label="$t('events.index')" v-if="isEditor"/>
      <nav-list-item to="logs" icon="chart" :label="$t('logs.index')" v-if="isEditor"/>
    </nav-list>

    <favorites></favorites>

    <template slot="footer">
      <timer></timer>
    </template>

  </sidebar>
</template>

<script>
  import { mapGetters } from 'vuex'
  import Timer from '../logs/Timer'
  import Favorites from './Favorites.vue'
  import Dropdown from './Dropdown.vue'
  import Sidebar from './Sidebar'
  import NavList from './NavList'
  import NavListItem from './NavListItem'

  export default {
    components: {
      Timer,
      NavList,
      NavListItem,
      Sidebar,
      Favorites,
      Dropdown
    },
    computed: {
      ...mapGetters({
        currentUser: 'currentUser',
        isEditor: 'isEditor',
        notifications: 'notifications'
      }),
      unread () {
        const unreadItems = this.notifications.filter(n => n.read_at === null).length
        return unreadItems ? unreadItems.toString() : null
      }
    },
    methods: {
      logout () {
        axios.get('logout').then(() => {
          window.location.href = '/login'
        })
      }
    }

  }
</script>

<style>
  .logo {
    float: left;
    width: 50px;
    height: 31px;
    background: url('../../../images/logo-dark.svg') no-repeat center;
    background-size: contain;
  }
</style>