<template>
  <modal-content :save="save" :cancel="cancel" :title="title">
    <form-input label="cards.form.title" v-model="card.title"/>
    <div class="row">
      <div class="col-md-6">
        <form-input type="select"
                    :options="userList"
                    v-model="card.assigned_to"
                    label="cards.form.assignedTo"/>
      </div>
      <div class="col-md-6">
        <datepicker v-model="card.due_date" label="cards.form.dueDate"/>
      </div>
    </div>
    <editor v-model="card.description"/>
  </modal-content>
</template>

<script>
  import { mapGetters } from 'vuex'
  import Datepicker from '../common/Datepicker.vue'
  import Editor from '../common/Editor.vue'
  import FormInput from '../common/FormInput.vue'
  import ModalContent from '../common/ModalContent.vue'

  export default {
    components: {
      Datepicker,
      ModalContent,
      FormInput,
      Editor
    },
    data () {
      return {
        userList: []
      }
    },
    computed: {
      ...mapGetters({
        users: 'users',
        card: 'card'
      }),
      title () {
        return this.card.id
          ? 'cards.edit'
          : 'cards.create'
      }
    },
    mounted () {
      this.userList = this.users.map(u => {
        return ([u.id] = u.name)
      })
    },
    methods: {
      cancel () {
        this.card.id
          ? this.$root.$emit('showModal', require('./CardDetail.vue'))
          : this.$root.$emit('hideModal')
      },
      save () {
        this.$store.dispatch('saveCard', {urlParams: {projectId: this.card.project_id}, ...this.card})
          .then(() => this.$root.$emit('showModal', require('./CardDetail.vue')))
          .catch(err => this.$root.$emit('modalError', err.response.data))
      }
    }
  }
</script>