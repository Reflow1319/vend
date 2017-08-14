<template>
    <div>
        <title-bar>
            <template slot="left">{{ $t('users.index') }}</template>
            <template slot="right">
                <a @click="showSearch()" class="btn"><i class="icon-search"></i></a>
                <v-button @click="create()" type="primary">{{ $t('users.create') }}</v-button>
            </template>
        </title-bar>
        <div class="container">
            <loader ref="loader"></loader>
            <search-bar ref="searchBar"></search-bar>
            <div class="card-list">
                <card @click="show(user)" v-for="user in filteredUsers" :center="true" v-bind:key="user.id">
                    <img :src="user.image" alt="" class="avatar avatar-large" slot="title">
                    <template slot="body">
                        <b>{{ user.name }}</b><br>
                        {{ user.email }}
                    </template>
                    <template slot="footer">
                        {{ $t('users.roles.' + user.role.toString()) }}
                    </template>
                </card>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import UserEdit from '../components/users/UserEdit.vue'
    import UserDetail from '../components/users/UserDetail.vue'
    import Loader from '../components/common/Loader.vue'
    import SearchBar from '../components/users/SearchBar.vue'
    import VButton from '../components/common/Button'
    import Card from '../components/common/Card'
    import TitleBar from '../components/common/TitleBar'

    export default {
        name: 'UserList',
        components: {
            Loader,
            Card,
            TitleBar,
            VButton,
            SearchBar
        },
        data() {
            return {
                filter: {},
                filteredUsers: []
            }
        },
        computed: {
            ...mapGetters({
                users: 'users',
            })
        },
        mounted() {
            this.$refs.loader.start()
            this.$store.commit('setUsers', [])
            this.$store.dispatch('getUsers').then(users => {
                this.filteredUsers = users
                this.$refs.loader.stop()
            })
            this.on('filter:users', this.filterUsers)
        },
        methods: {
            filterUsers(filters) {
                let filterRole, filterName
                this.filteredUsers = this.users.filter(u => {
                    filterRole = filterName = true
                    if (filters.name) {
                        filterName = (
                            u.name.toLowerCase().indexOf(filters.name.toLowerCase()) > -1
                            || u.email.toLowerCase().indexOf(filters.name.toLowerCase()) > -1
                        )
                    }
                    if (filters.role) {
                        filterRole = u.role === filters.role
                    }
                    return (filterRole && filterName)
                })
            },
            create() {
                this.$store.commit('setUser', {})
                this.$root.$emit('showModal', UserEdit)
            },
            show(user) {
                this.$store.commit('setUser', user)
                this.$root.$emit('showModal', UserDetail)
            },
            showSearch() {
                this.$refs.searchBar.show = ! this.$refs.searchBar.show
            }
        }
    }
</script>
