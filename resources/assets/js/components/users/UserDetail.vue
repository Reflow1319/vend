<template>
    <modal-content
        :left="{label: 'common.delete', action: destroy}"
        :right="{label: 'common.edit', action: edit}">
        <div class="form-group">
            <div class="avatar-upload">
                <img :src="user.image" alt="" class="avatar-upload-image">
            </div>
        </div>
        <div class="text-center">
            <div class="form-group">
                {{ user.email }}
            </div>
            <div class="form-group">
                {{ $t('users.roles.' + user.role) }}
            </div>
        </div>
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import ModalContent from '../common/ModalContent.vue'
    import EditUser from './UserEdit.vue'

    export default {
        components: {
            ModalContent
        },
        computed: {
            ...mapGetters({
                user: 'user'
            })
        },
        methods: {
            edit() {
                this.$root.$emit('showModal', EditUser)
            },
            destroy() {
                this.$store.dispatch('deleteUser', this.user).then(() => {
                    this.$root.$emit('hideModal')
                })
            }
        }
    }
</script>