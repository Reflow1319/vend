<template>
    <button :class="classes" @click="$emit('click', $event)">
        <i :class="'icon-' + icon" v-if="icon"></i>
        <slot />
    </button>
</template>

<script>
    export default {
      props: {
        label: String,
        icon: String,
        type: String
      },
      computed: {
        classes () {
          return this.type
            ? 'btn btn-' + this.type
            : 'btn'
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    @mixin make-button($color, $bgcolor, $border-color) {
        color: $color;
        border-color: $border-color;
        background: $bgcolor;
        &:hover {
            border-color: darken($bgcolor, 5);
            background: darken($bgcolor, 5);
        }
    }

    .btn {
        display: inline-block;
        padding: $padding-base;
        border-radius: $border-radius-base;
        font-family: inherit;
        border: 1px solid transparent;
        cursor: pointer;
        text-align: center;
        transition-duration: .2s;
        text-transform: uppercase;
        font-size: 13px;
        line-height: 21px;
        background: transparent;

        &:hover {
            position: relative;
        }

        &-block {
            display: block;
        }

        &-primary {
            @include make-button(#fff, #77a4f3, darken(#77a4f3, 5));
        }

        &-primary-o {
            @include make-button(darken($blue, 15), #fff, $blue);
        }

        &-default {
            @include make-button($common, #fff, $light-gray);
            &:hover {
                border-color: $blue;
                background: #fff;
            }
        }

        &.active {
            background-color: darken($light-gray, 3);
            border-color: darken($light-gray, 5);
        }

        &-sm {
            padding: 4px 10px;
            font-size: 13px;
        }

        &-dark {
            @include make-button(#fff, transparent, rgba(255, 255, 255, .2));
            color: #fff;
        }
        &-loading {
            background-size: contain;
            margin-right: 5px;
            background: url('../../../images/loading.svg') no-repeat center;
            background-size: cover;
            width: 24px;
            height: 24px;
            display: inline-block;
            vertical-align: middle;
        }
    }
</style>