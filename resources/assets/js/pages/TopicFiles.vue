<template>
    <div>
        <topic-header :topic="topic"></topic-header>
        <div class="container">
            <div class="wbox">
                <loader ref="loader"></loader>
                <file-list :files="files"></file-list>
                <div class="no-record" v-if="noRecord">
                    {{ $t('files.empty') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FileList from '../components/files/FileList.vue'
    import TopicHeader from '../components/topics/TopicHeader.vue'
    import Loader from '../components/common/Loader.vue'
    import {mapGetters} from 'vuex'

    export default {
      data () {
        return {
          noRecord: false
        }
      },
      components: {
        FileList,
        Loader,
        TopicHeader
      },
      computed: {
        ...mapGetters({
          topic: 'topic',
          files: 'topicFiles'
        })
      },
      mounted () {
        this.$refs.loader.start()
        this.$store.dispatch('getTopic', {id: this.$route.params.id}).then(() => {
          this.$store.dispatch('getTopicFiles', {urlParams: {topicId: this.$route.params.id}}).then(() => {
            this.$refs.loader.stop()
            if (this.files.length === 0) this.noRecord = true
          })
        })
      }
    }
</script>
