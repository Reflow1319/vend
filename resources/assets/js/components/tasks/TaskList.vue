<template>
  <div>
    <div class="form-group task-list">
      <div class="media media-sm cursor-pointer" v-for="task in value">
        <div class="media-left" @click="toggle(task, $event)">
          <i :class="task.is_completed ? 'icon icon-checked text-green' : 'icon icon-unchecked'"></i>
        </div>
        <div @click="toggle(task, $event)" class="media-body">
          {{ task.title }}
        </div>
        <div class="media-right">
          <a href="#" @click.prevent="deleteTask(task)" class="pull-right"><i class="icon-delete"></i></a>
        </div>
      </div>
    </div>
    <form @submit.prevent="save()">
      <div class="input-group">
        <input type="text" v-model="newTask.title" class="form-control" placeholder="Task title">
        <button class="btn btn-primary">{{ $t('tasks.create') }}</button>
      </div>
    </form>
  </div>
</template>

<script>
  import http from '../../http'

  export default {
    props: {
      value: {
        type: Array,
        default: () => []
      },
      url: {
        type: String,
        default: ''
      }
    },
    data () {
      return {
        newTask: {
          is_completed: false
        }
      }
    },
    methods: {
      save () {
        http.post(this.url + '/tasks', this.newTask).then(res => {
          this.newTask.title = ''
          this.value.push(res.data)
          this.$emit('change', this.value)
        })
      },
      toggle (task) {
        task.is_completed = !task.is_completed
        http.put(this.url + '/tasks/' + task.id, task).then(res => {
          this.$emit('change', this.value)
        })
      },
      deleteTask (task) {
        http.delete(this.url + '/tasks/' + task.id).then(() => {
          let taskIndex = _.findIndex(this.value, {id: task.id})
          this.value.splice(taskIndex, 1)
        })
      }
    }
  }
</script>
