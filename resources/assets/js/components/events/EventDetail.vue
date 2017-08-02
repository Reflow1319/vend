<template>
    <div>
        <div class="modal-header">
            <div class="modal-header-left">
                <a @click="destroy(event)">{{ $t('common.delete') }}</a>
            </div>
            <div class="modal-header-right">
                <a @click.prevent="edit(event)">{{ $t('common.edit') }}</a>
            </div>
            <div class="modal-title">{{ event.title }}</div>
            <div class="modal-meta">
                <span class="label label-primary">{{ dateFormat(event.start) }}</span>
            </div>
        </div>
        <div class="modal-body">
            <div v-html="event.description" v-if="event.description !== ''"></div>
        </div>
    </div>

</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        computed: {
            ...mapGetters({
                event: 'event'
            })
        },
        mounted() {

        },
        methods: {
            edit(event) {
                this.emit('showModal', 'event-form', this.$store.dispatch('getEvent', {id: event.id}))
            },
            destroy(event) {
                this.$store.dispatch('deleteEvent', {id: event.id}).then(() => {
                    this.emit('hideModal')
                })
            }
        }
    }
</script>
