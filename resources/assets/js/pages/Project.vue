<template>
  <div>
    <title-bar>
      <template slot="left">
        <router-link to="/">{{ $t('projects.index') }}</router-link>
        <i class="icon-arrow-right title-bar-arrow"></i>
        {{ project.title }}
        <span class="label label-warn title-bar-label" v-if="project.due_date">
                    {{ dateFormat(project.due_date) }}
                </span>
      </template>
      <template slot="right">
        <a @click="editProject(project)" class="btn"><i class="icon-pencil"></i></a>
        <a @click="toggleInfo()" class="btn" :class="{'active' : infoVisible}"><i class="icon-search"></i></a>
        <favorite-button type="projects" :id="project.id"></favorite-button>
      </template>
    </title-bar>

    <div class="container-fluid">

      <search-bar :project="project" :open="infoVisible"></search-bar>

      <loader ref="loader"></loader>
      <div class="column-list" v-if="loaded">
        <div class="column"
             :class="{'column-archive': column.is_archive}"
             :data-id="column.id"
             v-for="column in project.columns"
             v-show="hasToShowColumn(column)">
          <div class="column-header">
            <a @click="addCard(column)" class="column-add-card" v-if="currentUser.role !== 'client'">
              <i class="icon icon-plus"></i>
            </a>
            <div class="column-title">
              <i class="icon icon-checked" v-if="markToComplete(column)"></i>
              {{ column.title }} <span v-if="markToComplete(column)"
                                       class="text-muted small">{{ $t('projects.completedColumn') }}</span>
            </div>
          </div>
          <div class="column-cards">
            <card v-for="card in columnCards(column)" :card="card" v-bind:key="card.id"></card>
          </div>
        </div>
      </div>
      <div class="column-archive-toggle"
           @click="showArchiveColumns = !showArchiveColumns"
           v-show="archiveColumns.length > 0">
        <span>{{ $t('projects.toggleArchive') }}</span>
      </div>
    </div>
  </div>
</template>

<script>
  import $ from 'jquery'
  import _ from 'lodash'
  import {dateFormat} from '../utils'
  import http from '../http'
  import SearchBar from '../components/projects/SearchBar.vue'
  import { mapGetters } from 'vuex'
  import Loader from '../components/common/Loader.vue'
  import Card from '../components/cards/Card.vue'
  import CardEdit from '../components/cards/CardEdit.vue'
  import ProjectEdit from '../components/projects/ProjectEdit.vue'
  import FavoriteButton from '../components/common/FavoriteButton.vue'
  import TitleBar from '../components/common/TitleBar.vue'
  import sortable from 'jquery-ui/ui/widgets/sortable'
  import moment from 'moment'

  export default {
    components: {
      SearchBar,
      TitleBar,
      FavoriteButton,
      Card,
      Loader
    },
    computed: {
      ...mapGetters({
        project: 'project',
        cards: 'cards',
        currentUser: 'currentUser'
      }),
      archiveColumns () {
        return _.filter(this.project.columns, {is_archive: true})
      }
    },
    data () {
      return {
        columns: null,
        sortable: null,
        loaded: false,
        infoVisible: false,
        lastColumn: null,
        showArchiveColumns: false,
        filteredCards: []
      }
    },
    mounted () {
      this.navigateTo()

      this.$root.$on('filter:cards', this.filterCards)

      this.$store.subscribe((mutation, state) => {
        if (mutation.type === 'deleteCard') {
          this.filteredCards = state.cards.cards
        }
      })
    },
    methods: {
      dateFormat,
      filterCards (filters) {
        let filterUser, filterDate, filterTitle
        this.filteredCards = this.cards.filter(c => {
          filterUser = filterDate = filterTitle = true
          if (filters.user) {
            filterUser = c.assigned_to === filters.user.id
          }
          if (filters.title) {
            filterTitle = c.title.toLowerCase().indexOf(filters.title.toLowerCase()) !== -1
          }
          if (filters.dueDate && filters.dueDate.length > 0) {
            let dueDate = moment(c.due_date, 'YYYY-MM-DD')
            filterDate = dueDate > filters.dueDate[0] &&
              dueDate <= filters.dueDate[1]
          }
          return (filterUser && filterDate && filterTitle)
        })
      },
      navigateTo () {
        let projectId = this.$route.params.id

        this.$refs.loader.start()
        this.$store.commit('setCards', [])
        this.$store.commit('setProject', {})
        this.$store.dispatch('getProject', {id: projectId}).then(() => {
          this.$store.dispatch('getCards', {urlParams: {projectId: projectId}}).then(cards => {
            this.filteredCards = cards
            this.$refs.loader.stop()
            this.loaded = true

            // Bless you
            setTimeout(() => {
              this.bindSortable()
            }, 100)
          })
        })
      },
      removeSortable () {
        if (this.columns) {
          this.columns.sortable('destroy')
        }
      },
      bindSortable () {
        this.columns = $(this.$el).find('.column-cards')
        if (this.columns.length === 0) {
          return
        }
        this.sortable = this.columns.sortable({
          connectWith: '.column-cards',
          receive: (e, ui) => {
            const columnId = $(ui.item).closest('.column').data('id')
            const column = _.find(this.project.columns, {id: columnId})
            const cardId = $(ui.item).data('id')
            http.put('projects/' + this.project.id + '/cards/' + cardId + '/column', {
              column_id: column.id,
              is_completed: this.markToComplete(column)
            })
              .then(res => {
                _.map(this.cards, card => {
                  if (card.id === cardId) {
                    card.is_completed = res.data.is_completed
                  }
                })
              })
          }
        })
      },
      toggleInfo () {
        this.infoVisible = !this.infoVisible
      },
      columnCards (column) {
        return this.filteredCards.filter(card => card.column_id === column.id)
      },
      hasToShowColumn (column) {
        return !column.is_archive || (column.is_archive && this.showArchiveColumns)
      },
      addCard (column) {
        this.$store.commit('setCard', {
          column_id: column.id,
          project_id: this.project.id,
          is_completed: this.markToComplete(column)
        })
        this.$root.$emit('showModal', CardEdit)
      },
      markToComplete (column) {
        return column.is_archive || (this.lastColumn && this.lastColumn.id === column.id)
      },
      editProject (project) {
        this.$store.commit('setProject', project)
        this.$root.$emit('showModal', ProjectEdit)
      },
      updateProject (project) {
        this.lastColumn = _.filter(project.columns, {is_archive: false}).reverse()[0]
        this.removeSortable()
        this.bindSortable()
      }
    },
    watch: {
      '$route': 'navigateTo',
      'project': {
        handler: 'updateProject',
        deep: true
      }
    }
  }
</script>
