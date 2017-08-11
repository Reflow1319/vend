<template>
    <modal-content
        :title="event.id ? 'events.edit' : 'events.create'"
        :save="save"
        :cancel="cancel">
        <error-message ref="errorMessage" />
        <form-input label="events.title" v-model="event.title" />
        <div class="row">
            <div class="col-md-6">
                <datepicker v-model="event.start" :has-time="true" label="events.start" />
            </div>
            <div class="col-md-6">
                <datepicker v-model="event.end" :has-time="true" label="events.end" />
            </div>
        </div>
        <form-input label="events.location" v-model="event.location" />
        <editor v-model="event.description" placeholder="events.description" />
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Datepicker from '../common/Datepicker.vue'
    import Editor from '../common/Editor.vue'
    import FormInput from '../common/FormInput.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import ModalContent from '../common/ModalContent.vue'

    export default {
        components: {
            Datepicker,
            ModalContent,
            FormInput,
            ErrorMessage,
            Editor
        },
        computed: {
            ...mapGetters({
                event: 'event'
            })
        },
        methods: {
            save() {
                this.$store.dispatch('saveEvent', this.event)
                    .then(() => {
                        this.emit('hideModal')
                    })
                    .catch(err => {
                        this.setErrors(err)
                    })
            },
            cancel() {
                this.emit('hideModal')
            }
        }
    }
</script>