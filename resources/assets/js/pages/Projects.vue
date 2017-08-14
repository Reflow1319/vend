<template>
    <div>
        <title-bar :title="$t('projects.index')">
            <template slot="tabs">
                <router-link :to="{name: 'projects'}" exact>{{ $t('projects.active') }}</router-link>
                <router-link :to="{name: 'projects-archive'}">{{ $t('projects.archive') }}</router-link>
            </template>
            <template slot="right">
                <ui-button @click="createProject()" type="primary">{{ $t('projects.create') }}</ui-button>
            </template>
        </title-bar>

        <div class="container">
            <loader :loading="loading"></loader>

            <div class="row" v-if="projects.length">
                <div class="col-md-4" v-for="project in projects" :key="project.id">
                    <card @click="showProject(project)" :title="project.title" height="200px">
                        <template slot="body">
                            <labeled-prop :label="$t('projects.dueDate')" :prop="dateFormat(project.due_date)" />
                        </template>
                        <template slot="footer">
                            <progress-bar :current="project.completed_cards_count" :full="project.cards_count" />
                            <avatar-list :images="userImages(project)" />
                        </template>
                    </card>
                </div>
            </div>

            <div v-if="empty && $route.name !== 'projects-archive'" class="text-center">
                <div class="no-record mb-md">
                    {{ $t('projects.empty') }}
                </div>
                <a @click="createProject()" class="btn btn-default">{{ $t('projects.create') }}</a>
            </div>
            <div v-if="empty && $route.name == 'projects-archive'" class="text-center">
                <div class="no-record mb-md">
                    {{ $t('projects.emptyArchive') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Loader from '../components/common/Loader.vue'
    import Card from '../components/common/Card'
    import ProjectEdit from '../components/projects/ProjectEdit.vue'
    import ProgressBar from '../components/common/ProgressBar'
    import AvatarList from '../components/common/AvatarList'
    import TitleBar from '../components/common/TitleBar'
    import UiButton from '../components/common/Button'
    import LabeledProp from '../components/common/LabeledProp'

    export default {
        components: {
            Loader,
            LabeledProp,
            ProgressBar,
            UiButton,
            TitleBar,
            AvatarList,
            Card
        },
        computed: {
            ...mapGetters({
                projects: 'projects'
            })
        },
        data() {
            return {
                loading: false,
                loaded: false,
                empty: false,
                displayUser: 10
            }
        },
        mounted() {
            this.fetch()
        },
        methods: {
            userImages(project) {
                return project.users.map(u => u.image)
            },
            fetch() {
                this.loading = true
                this.$store.commit('setProjects', [])
                this.empty = false
                let isArchive = this.$route.name === 'projects-archive'
                this.$store.dispatch('getProjects', {_url: 'projects/?archive=' + (isArchive ? 1 : 0)}).then(() => {
                    if (this.projects.length === 0) {
                        this.empty = true
                    }
                    this.loading = false
                })
            },
            progress(project) {
                if (project.cards_count === 0)
                    return false

                return Math.round((project.completed_cards_count / project.cards_count) * 100)
            },
            createProject() {
                this.$store.commit('setProject', {columns: [], users: []})
                this.$root.$emit('showModal', ProjectEdit);
            },
            showProject(project) {
                this.$router.push({name: 'project', params: {id: project.id}})
            },
        },
        watch: {
            '$route': 'fetch'
        }
    }
</script>
