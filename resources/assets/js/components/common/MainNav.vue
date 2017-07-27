<template>
    <header class="header">

        <div class="header-top">
            <a href="#" class="header-title"></a>

            <div class="header-top-right">
                <dropdown :right="true">
                    <img :src="currentUser.image" alt="" class="avatar" slot="dropdownToggle">
                    <div slot="dropdownContent">
                        <b>{{ currentUser.name }}</b>
                        <div class="text-muted">{{ $t('users.roles.' + currentUser.role) }}</div>
                        <div class="dropdown-links">
                            <a href="/logout">{{ $t('auth.logout') }}</a>
                        </div>
                    </div>
                </dropdown>
            </div>
        </div>

        <div class="header-blocks">
            <div v-if="activeTab == 'navigation'">
                <nav class="header-nav">
                    <router-link :to="{name: 'projects'}" exact><i class="icon-stack"></i> {{ $t('projects.index') }}</router-link>
                    <router-link :to="{name: 'notifications'}" exact><i class="icon-flame"></i>
                        {{ $t('notifications.index') }}
                        <span class="badge badge-warn" v-if="unReadNotifications.length > 0">{{ unReadNotifications.length }}</span>
                    </router-link>
                    <router-link :to="{name: 'topics'}"><i class="icon-bubbles"></i> {{ $t('topics.index') }}</router-link>
                    <router-link :to="{name: 'users'}" v-if="isEditor"><i class="icon-users"></i> {{ $t('users.index') }}</router-link>
                    <router-link :to="{name: 'calendar'}" v-if="isEditor"><i class="icon-calendar"></i> {{ $t('events.index') }}</router-link>
                    <router-link :to="{name: 'logs'}" v-if="isEditor"><i class="icon-chart"></i> {{ $t('logs.index') }}</router-link>

                </nav>
                <favorites></favorites>
            </div>
            <div v-if="activeTab == 'notifications'">
                <notifications></notifications>
            </div>
        </div>
        <div class="header-bottom-nav">
            <timer></timer>
        </div>
    </header>
</template>

<script>
    import {mapGetters} from 'vuex'

    import Timer from '../logs/Timer'
    import Notifications from './Notifications.vue'
    import Favorites from './Favorites.vue'
    import Dropdown from './Dropdown.vue'

    export default {
        data() {
            return {
                activeTab: 'navigation'
            }
        },
        components: {
            Timer,
            Favorites,
            Notifications,
            Dropdown
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser',
                isEditor: 'isEditor',
                unReadNotifications: 'unReadNotifications'
            })
        },
        methods: {
            switchTab(tab) {
                this.activeTab = tab
            },
            logout() {
                axios.get('logout').then(() => {
                    window.location.href = '/login'
                })
            }
        }

    }
</script>
