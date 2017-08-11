<template>
    <modal-content
        :title="topic.id ? $t('topics.edit') : $t('topics.create')"
        :left="topic.id ? {label: 'common.delete', action: deleteTopic} : null"
        :save="save"
        :cancel="cancel">
            <error-message ref="errorMessage" />
            <form-input label="topics.form.title" v-model="topic.title" />
            <div>
                <label class="form-label">{{ $t('topics.form.members') }}</label>
                <members-list :users="users" v-model="topic.users" />
            </div>
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import MembersList from '../common/MembersList.vue'
    import ModalContent from '../common/ModalContent.vue'
    import FormInput from '../common/FormInput.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'

    export default {
        components: {
            MembersList,
            ModalContent,
            FormInput,
            ErrorMessage
        },
        computed: {
            ...mapGetters({
                topic: 'topic',
                users: 'users'
            })
        },
        methods: {
            save() {
                this.$store.dispatch('saveTopic', this.topic).then(topic => {
                    this.$root.$emit('hideModal')
                    this.$router.push({name: 'topic', params: {id: topic.id}})
                }).catch(err => {
                    this.setErrors(err)
                })
            },
            cancel() {
                this.emit('hideModal')
            },
            deleteTopic() {
                this.$store.dispatch('deleteTopic', this.topic).then(() => {
                    this.$root.$emit('hideModal')
                    this.$router.push({name: 'topics'})
                })
            }
        }
    }
</script>
