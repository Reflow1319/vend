<template>
    <modal-content
        :title="user.id ? 'users.edit'  : 'users.create'"
        :save="save"
        :cancel="cancel">

        <error-message ref="errorMessage" />
        <div class="form-group text-center" v-if="user.id">
            <div class="mb-md">
                <img :src="user.image" alt="" class="avatar-upload-image">
            </div>
            <uploader
                    type="user"
                    :compact="true"
                    :uploaded="switchImage"
                    :text="$t('users.uploadFile')"
                    :single="true" />
        </div>
        <form-input label="users.form.name" v-model="user.name" />
        <form-input label="users.form.email" v-model="user.email" />
        <form-input label="users.form.role" type="select" v-model="user.role" :options="userRoles" />
        <div class="divider"></div>
        <h4 v-if="user.id">{{ $t('users.form.changePassword') }}</h4>
        <form-input label="users.form.password" v-model="user.password" />
        <form-input label="users.form.passwordConfirmation" v-model="user.password_confirm" />

    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Uploader from '../common/Uploader.vue'
    import ModalContent from '../common/ModalContent.vue'
    import FormInput from '../common/FormInput.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'

    export default {
        components: {
            Uploader,
            FormInput,
            ModalContent,
            ErrorMessage
        },
        data() {
            return {
                userRoles: {
                    '': 'Choose',
                    admin: this.$t('users.roles.admin'),
                    editor: this.$t('users.roles.editor'),
                    client: this.$t('users.roles.client')
                }
            }
        },
        computed: {
            ...mapGetters({
                user: 'user'
            })
        },
        mounted() {

        },
        methods: {
            save() {
                this.$store.dispatch('saveUser', this.user)
                    .then(() => this.$root.$emit('hideModal'))
                    .catch(err => this.setErrors(err))
            },
            switchImage(file) {
                this.user.image = file.thumbnail;
            },
            cancel() {
                this.emit('hideModal')
            }
        }
    }
</script>