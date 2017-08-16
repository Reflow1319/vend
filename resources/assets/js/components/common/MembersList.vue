<template>
    <div>
        <div class="media media-sm cursor-pointer" v-for="user in users" @click="toggleUser(user)">
            <div class="media-left">
                <i :class="inSelected(user) ? 'text-green icon-checked' : 'icon-unchecked'"></i>
            </div>
            <div class="media-body">
                <img :src="user.image" alt="" class="avatar">
                <b>{{ user.name }}</b><br>
                <span class="text-muted">{{ user.email }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      props: {
        users: {
          type: Array,
          default: () => []
        },
        value: {
          type: Array,
          default: () => []
        }
      },
      methods: {
        inSelected (user) {
          return _.findIndex(this.value, {id: user.id}) > -1
        },
        toggleUser (user) {
          this.inSelected(user)
            ? this.value.splice(_.findIndex(this.value, {id: user.id}), 1)
            : this.value.push(user)

          this.$emit('input', this.value)
        }
      }
    }
</script>