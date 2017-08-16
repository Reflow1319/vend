<template>
  <sidebar :right="true" background="#45516a" :open="open">
    <div class="form-group text-right">
      <a @click="clearFilter()" class="text-muted"><i class="icon-delete"></i> {{ $t('filter.clear') }}</a>
    </div>

    <div class="form-group">
      <input type="text" class="form-control" @keyup="setQuery" v-model="query">
    </div>

    <nav-list :title="$t('filter.dueDate')" :light="true">
      <nav-list-item
              :label="$t(filter)"
              :key="key"
              :classes="{'active' : selectedDate == key}"
              @click="setDueDate(key)"
              v-for="(filter, key) in dateFilters"/>
    </nav-list>

    <nav-list :title="$t('filter.assignedTo')" :light="true">
      <media
              :image="user.image"
              :key="user.id"
              :classes="{'active' : selectedUser == user.id}"
              :small="true"
              v-for="user in project.users"
              @click="setUser(user)">
        <template slot="body">
          {{ user.name }}
          <div class="text-muted">{{ user.email }}</div>
        </template>
      </media>
    </nav-list>

  </sidebar>
</template>

<script>
  import {strToInterval} from '../../utils'
  import Datepicker from '../common/Datepicker.vue'
  import Sidebar from '../common/Sidebar.vue'
  import Media from '../common/Media.vue'
  import NavList from '../common/NavList.vue'
  import NavListItem from '../common/NavListItem.vue'

  export default {
    components: {
      Datepicker,
      Sidebar,
      NavList,
      Media,
      NavListItem
    },
    props: {
      project: Object,
      open: Boolean
    },
    data () {
      return {
        filter: {},
        selectedUser: null,
        selectedDate: null,
        query: '',
        visible: false,
        dateFilters: {
          thisWeek: 'filter.thisWeek',
          nextWeek: 'filter.nextWeek',
          thisMonth: 'filter.thisMonth',
          nextMonth: 'filter.nextMonth'
        }
      }
    },
    methods: {
      strToInterval,
      show () {
        this.visible = true
        this.$emit('changed', this.visible)
      },
      hide () {
        this.visible = false
        this.$emit('changed', this.visible)
      },
      toggle () {
        this.visible = !this.visible
        this.$emit('changed', this.visible)
      },
      setUser (user) {
        this.filter = Object.assign({}, this.filter, {user: user})
        this.selectedUser = user.id
        this.filterCard()
      },
      setDueDate (date) {
        this.selectedDate = date
        this.filter = Object.assign({}, this.filter, {
          dueDate: this.strToInterval(date)
        })
        this.filterCard()
      },
      setQuery () {
        this.filter = Object.assign({}, this.filter, {
          title: this.query.trim() !== '' ? this.query : null
        })
        this.filterCard()
      },
      clearFilter () {
        this.filter = {}
        this.query = ''
        this.selectedDate = null
        this.selectedUser = null
        this.filterCard()
      },
      filterCard () {
        this.$root.$emit('filter:cards', this.filter)
      }
    }
  }
</script>