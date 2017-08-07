<template>
    <div class="modal" :class="{'in' : visible}" :style="visible ? 'display: block' : ''" @click="hide()">
        <div class="modal-dialog" @click.stop="">
            <div class="modal-content">
                <div class="modal-loading" v-show="loading"></div>
                <error-message ref="errorMessage"></error-message>
                <component :is="view" ref="view" v-show=" ! loading"></component>
            </div>
        </div>
    </div>
</template>

<script>
    import ShowCard from '../cards/ShowCard.vue'
    import EditCard from '../cards/EditCard.vue'
    import EditProject from '../projects/EditProject.vue'
    import ShowUser from '../users/ShowUser.vue'
    import EditUser from '../users/EditUser.vue'
    import EventDetail from '../events/EventDetail.vue'
    import TopicForm from '../topics/TopicForm.vue'
    import ShowMessage from '../messages/ShowMessage.vue'
    import MessageForm from '../messages/MessageForm.vue'
    import EventForm from '../events/EventForm.vue'
    import MessageDetail from '../messages/MessageDetail.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'

    export default {
        name: 'modal',
        components: {
            ShowCard,
            ShowUser,
            EditUser,
            TopicForm,
            MessageForm,
            MessageDetail,
            EventDetail,
            EventForm,
            ShowMessage,
            EditCard,
            EditProject,
            ErrorMessage
        },
        data() {
            return {
                prefetch: [
                    'show-card'
                ],
                error: null,
                view: null,
                visible: false,
                loading: true
            }
        },
        mounted() {
            this.$root.$on('showModal', (view, cb) => {
                this.error = null
                this.setErrors(null)
                this.visible = true
                if(cb) {
                    this.loading = true
                    cb.then(() => {
                        this.view = view
                        this.loading = false
                    })
                        .catch(err => {
                            this.setErrors(err)
                            this.loading = false
                            this.view = ''
                        })
                } else {
                    this.view = view
                    this.loading = false
                }
            })
            this.$root.$on('hideModal', () => {
                this.view = null
                this.visible = false
                this.loading = true
                this.error = null
            })

            document.addEventListener('keyup', this.hideByKey)
        },
        beforeDestroy() {
            document.removeEventListener('keyup', this.hideByKey)
        },
        methods: {
            hideByKey(e) {
                if (e.keyCode === 27) {
                    this.hide()
                }
            },
            hide(e) {
                this.visible = false
                this.view = null
                this.loading = true
            },
            show() {
                this.visible = true
            }
        }
    }
</script>
