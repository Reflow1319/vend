<template>
  <div class="uploader" :class="{'uploader-compact btn btn-default': compact}">
    <div class="uploader-text">
      <span class="uploader-loading-icon" v-if="loading"></span>
      <span>{{ loading ? 'Uploading...' : 'Upload files' }}</span>
    </div>
    <div v-if="percentCompleted !== 0" class="uploader-percent" :style="'width:' + percentCompleted + '%'"></div>
    <input type="file" name="userfile"
           class="uploader-field"
           @change="uploadFile"
           multiple>
  </div>
</template>

<script>
  import http from '../../http'

  export default {
    props: ['type', 'parent', 'compact', 'text', 'single', 'uploaded'],
    data () {
      return {
        loading: false,
        percentCompleted: 0
      }
    },
    methods: {
      uploadFile (e) {
        const files = e.target.files
        this.loading = true
        for (let i = 0; i < files.length; i++) {
          const formData = new FormData()
          formData.append('userfile', files[i])
          formData.append('type', this.type)
          if (this.parent) {
            formData.append('parent', this.parent)
          }

          const options = {
            onUploadProgress: (progressEvent) => {
              this.percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
            }
          }

          http.post('upload', formData, options).then(res => {
            this.uploaded(res.data)
            this.percentCompleted = 0
            this.loading = false
          })
        }
      }
    }
  }
</script>