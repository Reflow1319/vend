<template>
    <modal-content
        :title="event.title"
        :left="{label: 'common.delete', action: destroy}"
        :right="{label: 'common.edit', action: edit}">

        <span class="label label-primary">{{ dateFormat(event.start) }}</span>
        <div v-html="event.description" v-if="event.description !== ''"></div>

    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import ModalContent from '../common/ModalContent.vue'
    import EventForm from './EventForm.vue'

    export default {
        components: {
            ModalContent
        },
        computed: {
            ...mapGetters({
                event: 'event'
            })
        },
        methods: {
            edit() {
                this.emit(
                    'showModal',
                    EventForm,
                    this.$store.dispatch('getEvent', {id: this.event.id})
                )
            },
            destroy() {
                this.$store.dispatch('deleteEvent', {id: this.event.id}).then(() => {
                    this.emit('hideModal')
                })
            }
        }
    }
</script>
