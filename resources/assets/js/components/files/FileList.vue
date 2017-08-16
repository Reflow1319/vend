<template>
    <div>
        <div class="media" v-for="file in files" :class="{'media-sm': small}">
            <div class="media-left">
                <img :src="file.thumbnail" alt="" class="media-image">
            </div>
            <div class="media-body">
                <div class="media-header"><a :href="file.path" target="_blank">{{ file.name }}</a></div>
                <span class="text-muted small">{{ fileSize(file.size) }} - {{ file.created_at }}</span>
            </div>
            <div class="media-actions">
                <a @click="deleteFile(file)"><i class="icon-delete"></i></a>
            </div>
        </div>
    </div>
</template>

<script>
    import {fileSize} from '../../utils'

    export default {
      props: ['files', 'small'],
      methods: {
        fileSize,
        deleteFile (file) {
          let index = _.findIndex(this.files, {id: file.id})
          axios.delete('files/' + file.id).then(res => {
            this.files.splice(index, 1)
          })
        }
      }
    }
</script>