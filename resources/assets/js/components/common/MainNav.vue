<template>
    <header class="header">

        <div class="header-top">
            <a href="#" class="header-title"></a>

            <div class="header-top-right">
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
            </div>
        </div>

        <div class="header-blocks">
            <nav class="nav-list nav-list-light">
                <router-link :to="{name: 'projects'}" exact :class="{'active' : $route.name === 'project'}"><i class="icon-stack"></i> {{ $t('projects.index') }}</router-link>
                <router-link :to="{name: 'notifications'}"><i class="icon-flame"></i>
                    {{ $t('notifications.index') }}
                    <span class="badge badge-warn" v-if="unread">{{ unread }}</span>
                </router-link>
                <router-link :to="{name: 'topics'}"><i class="icon-bubbles"></i> {{ $t('topics.index') }}</router-link>
                <router-link :to="{name: 'users'}" v-if="isEditor"><i class="icon-users"></i> {{ $t('users.index') }}</router-link>
                <router-link :to="{name: 'calendar'}" v-if="isEditor"><i class="icon-calendar"></i> {{ $t('events.index') }}</router-link>
                <router-link :to="{name: 'logs'}" v-if="isEditor"><i class="icon-chart"></i> {{ $t('logs.index') }}</router-link>
            </nav>
            <favorites></favorites>
        </div>
        <div class="header-bottom-nav">
            <timer></timer>
        </div>
    </header>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Timer from '../logs/Timer'
    import Favorites from './Favorites.vue'
    import Dropdown from './Dropdown.vue'

    export default {
        components: {
            Timer,
            Favorites,
            Dropdown
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser',
                isEditor: 'isEditor',
                notifications: 'notifications'
            }),
            unread() {
                return this.notifications.filter(n => n.read_at === null).length
            }
        },
        methods: {
            logout() {
                axios.get('logout').then(() => {
                    window.location.href = '/login'
                })
            }
        }

    }
</script>
