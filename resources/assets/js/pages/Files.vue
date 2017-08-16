<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">Files</div>
        </div>
        <div class="container">
            <loader ref="loader"></loader>
            <div class="wbox">
                <file-list :files="files" :small="false"></file-list>
            </div>
        </div>
    </div>
</template>

<script>
    import TitleBar from '../components/common/TitleBar.vue'
    import FileList from '../components/files/FileList.vue'
    import Loader from '../components/common/Loader.vue'

    export default {
      components: {
        FileList,
        TitleBar,
        Loader
      },
      data () {
        return {
          files: []
        }
      },
      methods: {
        deleteFile (file) {
          axios.delete('files/' + file.id).then(() => {
            this.files = this.files.filter(f => f.id !== file.id)
          })
        }
      },
      mounted () {
        this.$refs.loader.start()
        axios.get('files').then(res => {
          this.files = res.data
          this.$refs.loader.stop()
        })
      }
    }
</script>