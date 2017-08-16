<template>
  <div>
    <div class="mb-md comment-list">
      <div class="media media-sm small" v-for="comment in value">
        <div class="media-left">
          <img :src="comment.user.image" class="avatar">
        </div>
        <div class="media-body">
          <div class="media-header">
            <b>{{ comment.user.name }}</b>
            <span class="text-muted"> - {{ fromNow(comment.created_at) }}</span>
          </div>
          <div v-html="comment.content"></div>
        </div>
        <div class="media-right">
          <a @click="deleteComment(comment)" v-if="comment.user.id == currentUser.id">
            <i class="icon-delete"></i>
          </a>
        </div>
      </div>
    </div>
    <form action="#" @submit.prevent="save()">
      <div class="form-group">
        <editor v-model="newComment.content" placeholder="comments.placeholder"></editor>
      </div>
      <div class="text-right">
        <button class="btn btn-primary">{{ $t('comments.create') }}</button>
      </div>
    </form>
  </div>
</template>

<script>
  import http from '../../http'
  import { fromNow } from '../../utils'
  import _ from 'lodash'
  import Editor from '../common/Editor.vue'
  import { mapGetters } from 'vuex'

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
    components: {
      Editor
    },
    computed: {
      ...mapGetters({
        currentUser: 'currentUser'
      })
    },
    data () {
      return {
        newComment: {}
      }
    },
    methods: {
      fromNow,
      save () {
        http.post(this.url + '/comments', this.newComment).then(res => {
          this.value.push(res.data)
          this.newComment.content = ''
          this.$emit('change', this.value)
        })
      },
      deleteComment (comment) {
        http.delete(this.url + '/comments/' + comment.id).then(() => {
          let commentIndex = _.findIndex(this.value, {id: comment.id})
          this.value.splice(commentIndex, 1)
          this.$emit('input', this.value)
        })
      }
    }
  }
</script>
