<template>
    <form @submit.prevent="save()">
        <div class="modal-header">
            <div class="modal-title">{{ event.id ? $t('events.edit') : $t('events.create') }}</div>
        </div>
        <div class="modal-body">

            <error-message ref="errorMessage"></error-message>

            <div class="form-group">
                <label>{{ $t('events.title') }}</label>
                <input type="text" class="form-control" v-model="event.title">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>{{ $t('events.start') }}</label>
                    <div class="form-group">
                        <datepicker v-model="event.start" :has-time="true"></datepicker>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>{{ $t('events.end') }}</label>
                    <div class="form-group">
                        <datepicker v-model="event.end" :has-time="true"></datepicker>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{ $t('events.location') }}</label>
                <input type="text" class="form-control" v-model="event.location">
            </div>

            <editor ref="editor" :content="'asad'" :placeholder="$t('events.description')"></editor>

        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">{{ $t('common.save') }}</button>
        </div>
    </form>
</template>

<script>
    import Datepicker from '../common/Datepicker.vue'
    import Editor from '../common/Editor.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            Datepicker,
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
            }
        }
    }
</script>