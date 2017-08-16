<template>
    <div class="modal" :class="{'in' : visible}" :style="visible ? 'display: block' : ''" @click.self="hide()">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-loading" v-show="loading"></div>
                <error-message ref="errorMessage" />
                <component :is="view" ref="view" v-show=" ! loading" />
            </div>
        </div>
    </div>
</template>

<script>
    import ErrorMessage from '../common/ErrorMessage.vue'

    export default {
      name: 'modal',
      components: {
        ErrorMessage
      },
      data () {
        return {
          error: null,
          view: null,
          visible: false,
          loading: true
        }
      },
      mounted () {
        this.$root.$on('showModal', (view, cb) => {
          this.error = null
          this.$refs.errorMessage.set(null)
          this.visible = true
          if (cb) {
            this.loading = true
            cb.then(() => {
              this.view = view
              this.loading = false
            })
              .catch(err => {
                this.$refs.errorMessage.set(err)
                this.loading = false
                this.view = ''
              })
          } else {
            this.view = view
            this.loading = false
          }
        })
        this.$root.$on('hideModal', () => {
          this.view = null
          this.visible = false
          this.loading = true
          this.error = null
        })

        document.addEventListener('keyup', this.hideByKey)
      },
      beforeDestroy () {
        document.removeEventListener('keyup', this.hideByKey)
      },
      methods: {
        hideByKey (e) {
          if (e.keyCode === 27) {
            this.hide()
          }
        },
        hide () {
          this.visible = false
          this.view = null
          this.loading = true
        },
        show () {
          this.visible = true
        }
      }
    }
</script>
