<template>
    <div class="progress" v-if="progress !== false">
        <div class="text-muted small">{{ progress + '%' }}</div>
        <div class="progress-wrapper">
            <div class="progress-bar" :style="'width:' + progress + '%'"></div>
        </div>
    </div>
</template>

<script>
    export default {
      props: {
        current: {
          type: Number,
          default: 0
        },
        full: {
          type: Number,
          default: 0
        }
      },
      computed: {
        progress () {
          if (this.current === 0) { return false }

          return Math.round((this.current / this.full) * 100)
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .progress {
        padding: 0 0 15px 0;
        &-wrapper {
            background: $light-gray;
            height: 6px;
        }
        &-bar {
            background: $green;
            height: 6px;
        }
    }
</style>