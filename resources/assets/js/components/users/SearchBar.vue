<template>
    <sidebar :open="show" :right="true" background="#45516a">

        <div class="form-group text-right">
            <a @click="clearFilter()" class="text-muted"><i class="icon-delete"></i> {{ $t('filter.clear') }}</a>
        </div>

        <div class="mb-lg">
            <input type="text" class="form-control" @keyup="setQuery" v-model="query">
        </div>

        <nav-list :title="$t('users.role')" :light="true">
            <nav-list-item
                :label="$t('users.roles.' + role)"
                :classes="{'active' : selectedRole == role}"
                :key="role"
                @click="setRole(role)"
                v-for="role in roles"/>
        </nav-list>

    </sidebar>
</template>

<script>
    import moment from 'moment'
    import mixins from '../../mixins'
    import Datepicker from '../common/Datepicker.vue'
    import Sidebar from '../common/Sidebar.vue'
    import NavList from '../common/NavList.vue'
    import NavListItem from '../common/NavListItem.vue'

    export default {
        data() {
            return {
                filter: {},
                selectedRole: null,
                query: '',
                show: false,
                roles: ['admin', 'manager', 'editor', 'client']
            }
        },
        components: {
            Datepicker,
            NavList,
            NavListItem,
            Sidebar
        },
        methods: {
            setRole(role) {
                this.filter = Object.assign({}, this.filter, {role: role})
                this.selectedRole = role
                this.filterCard()
            },
            setQuery() {
                this.filter = Object.assign({}, this.filter, {
                    name: this.query.trim() !== '' ? this.query : null
                })
                this.filterCard()
            },
            clearFilter() {
                this.filter = {}
                this.query = ''
                this.selectedRole = null
                this.filterCard()
            },
            filterCard() {
                this.emit('filter:users', this.filter)
//                this.$store.commit('setUsersFilter', this.filter)
            },
        }
    }
</script>