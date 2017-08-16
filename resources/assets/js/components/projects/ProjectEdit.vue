<template>
    <modal-content
            :save="save"
            :cancel="cancel"
            :title="project.id ? 'projects.edit' : 'projects.create'"
            :left="{label: 'common.delete', action: destroy}"
            :right="{label: project.is_archive ? 'projects.unArchiveProject' : 'projects.archiveProject', action: toggleArchive}">
        <error-message ref="errorMessage" />
        <form-input label="projects.form.title" :large="true" v-model="project.title" />
        <datepicker label="projects.form.dueDate" v-model="project.due_date" />
        <tabs>
            <div slot="tabs">
                <a data-target="#columns" class="active">{{ $t('projects.columns') }}</a>
                <a data-target="#members">{{ $t('projects.members') }}</a>
            </div>
            <div slot="tabsContent">
                <div class="tabs-panel active" id="columns">
                    <project-columns v-model="project.columns" />
                </div>
                <div class="tabs-panel" id="members">
                    <member-list :users="users" v-model="project.users" />
                </div>
            </div>
        </tabs>
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import ProjectColumns from '../projects/ProjectColumns.vue'
    import MemberList from '../common/MembersList.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import Datepicker from '../common/Datepicker.vue'
    import Tabs from '../common/Tabs.vue'
    import FormInput from '../common/FormInput.vue'
    import ModalContent from '../common/ModalContent.vue'

    export default {
      components: {
        Tabs,
        FormInput,
        ModalContent,
        Datepicker,
        ProjectColumns,
        ErrorMessage,
        MemberList
      },
      computed: {
        ...mapGetters({
          project: 'project',
          users: 'users'
        })
      },
      methods: {
        save () {
          this.$store.dispatch('saveProject', this.project)
            .then(project => {
              this.$root.$emit('hideModal')
              this.$router.push({name: 'project', params: {id: project.id}})
            })
            .catch(err => this.$refs.errorMessage.set(err))
        },
        toggleArchive () {
          this.project.is_archive = !this.project.is_archive
          this.$store.dispatch('saveProject', this.project)
        },
        destroy () {
          this.$store.dispatch('deleteProject', this.project).then(() => {
            this.$router.push({name: 'projects'})
          })
        },
        cancel () {
          this.$root.$emit('hideModal')
        }
      }
    }
</script>
