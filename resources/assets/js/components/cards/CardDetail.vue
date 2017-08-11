<template>
    <modal-content
            :title="card.title"
            :left="{label: 'common.delete', action: deleteCard}"
            :right="{label: 'common.edit', action: editCard}">
        <template slot="modal-meta">
            <span v-if="card.assigned">
                <img :src="card.assigned.image" class="label-image"> {{ card.assigned.name }}
            </span>
            <span v-if="logsLength()">{{ elapsedFormat(logsLength()) }}</span>
            <span v-if="card.due_date">{{ dateFormat(card.due_date) }}</span>
            <a :class="isLogRunning() ? 'label-warn' : 'label-dark'"
               @click="toggleTimer(card)">
                <i class="icon-clock"></i>{{ $t('logs.startTimer') }}
                <span v-if="isLogRunning()">{{ elapsedFormat(timer) }}</span>
            </a>
        </template>

        <div v-html="card.description" v-if="card.description !== ''"></div>
        <tabs>
            <template slot="tabs">
                <a data-target="#tasks" class="active">{{ $t('cards.tasks') }}</a>
                <a data-target="#comments">{{ $t('cards.comments') }}</a>
                <a data-target="#files">{{ $t('cards.files') }}</a>
                <a data-target="#logs">{{ $t('cards.logs') }}</a>
            </template>
            <template slot="tabsContent">
                <div class="tabs-panel active" id="tasks">
                    <task-list
                        v-model="card.tasks"
                        :url="childUrl"
                        v-if="card.id" />
                </div>
                <div class="tabs-panel" id="comments">
                    <comment-list
                        v-model="card.comments"
                        :url="childUrl" />
                </div>
                <div class="tabs-panel" id="files">
                    <uploader :uploaded="addFile" :parent="card.id" type="card" text="Upload files" />
                    <file-list :files="card.files" :small="true" />
                </div>
                <div class="tabs-panel" id="logs">
                    <logs-list
                        :logs="card.logs"
                        :small="true"
                        :add="true"
                        :show-card="false"
                        :card="card" />
                </div>
            </template>
        </tabs>
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import CardEdit from './CardEdit.vue'
    import TaskList from '../tasks/TaskList.vue'
    import ModalContent from '../common/ModalContent.vue'
    import CommentList from '../comments/CommentList.vue'
    import FileList from '../files/FileList.vue'
    import LogsList from '../logs/LogsList.vue'
    import Tabs from '../common/Tabs.vue'
    import Uploader from '../common/Uploader.vue'

    export default {
        components: {
            TaskList,
            FileList,
            LogsList,
            Uploader,
            Tabs,
            CommentList,
            ModalContent
        },
        data() {
            return {
                fetched: false
            }
        },
        computed: {
            ...mapGetters({
                card: 'card',
                timer: 'timer',
                currentLog: 'currentLog'
            }),
            childUrl() {
                return 'projects/'+ this.card.project_id + '/cards/' + this.card.id
            }
        },
        mounted() {

        },
        methods: {
            addFile(file) {
                this.card.files.push(file)
            },
            logsLength() {
                return _.sumBy(this.card.logs, 'length')
            },
            editCard() {
                this.$root.$emit('showModal', CardEdit)
            },
            deleteCard() {
                this.$store.dispatch('deleteCard', {
                    urlParams: {projectId: this.card.project_id},
                    id: this.card.id
                })
                this.$root.$emit('hideModal')
            },
            toggleTimer(card) {
                this.$store.dispatch('toggleTimer', card)
            },
            isLogRunning() {
                return this.currentLog && this.currentLog.is_running && this.currentLog.card.id === this.card.id
            }
        }
    }
</script>
