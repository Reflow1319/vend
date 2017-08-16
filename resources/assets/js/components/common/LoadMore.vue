<template>
    <div class="load-more" v-if="!isLast">
        <a @click="load()" class="btn btn-default">
            <span class="btn-loading" v-if="loading"></span>
            {{ $t('common.loadMore') }}
        </a>
    </div>
</template>

<script>
    export default {
      props: ['options'],
      data () {
        return {
          loading: false
        }
      },
      computed: {
        isLast () {
          return this.options.current_page === this.options.last_page || this.options.total === 0
        }
      },
      methods: {
        load () {
          this.loading = true
          axios.get(this.options.next_page_url).then(res => {
            this.$emit('loaded', res.data)
            this.loading = false
          })
        }
      }
    }
</script>