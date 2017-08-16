<template>
  <div>
    <avatar :image="image" v-for="(image, index) in images" v-if="index <= count - 1" v-bind:key="image"/>
    <span class="counter" v-if="images.length > count">+{{ images.length - count }}</span>
  </div>
</template>

<script>
  import Avatar from './Avatar'

  export default {
    components: {
      Avatar
    },
    props: {
      count: {
        type: Number,
        default: 3
      },
      images: {
        type: Array,
        default: () => []
      }
    }
  }
</script>

<style lang="scss">
  @import "../../../sass/variables";

  .counter {
    background: $light-gray;
    border-radius: 16px;
    font-weight: bold;
    color: $text-gray;
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
  }
</style>