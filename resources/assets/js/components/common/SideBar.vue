<template>
    <div class="sidebar" :style="{backgroundColor: background}" :class="{'sidebar-dark': dark, 'sidebar-right': right, 'open': open}">
        <div class="sidebar-heading">
            <slot name="heading"></slot>
        </div>
        <div class="sidebar-body">
            <slot></slot>
        </div>
        <div class="sidebar-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
    export default{
      props: {
        background: {
          type: String,
          default: '#495891'
        },
        open: Boolean,
        dark: Boolean,
        right: Boolean,
        visible: {
          type: Boolean,
          default: true
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .sidebar {
        position: fixed;
        left: -230px;
        top: 0;
        bottom: 0;
        padding: 15px 20px;
        width: 230px;
        z-index: 30;
        display: flex;
        flex-direction: column;
        transition-duration: .3s;
        @include clearfix;

        &.open {
            left: 0;
        }

        &-right {
            right: -230px;
            left: auto;
            &.open {
                right: 0;
                left: auto;
                transform: translateX(0);
            }
        }

        &-dark {
            color: #fff;
        }

        &-heading {
            margin-bottom: 30px;
        }

        &-body {
            flex: 1;
            overflow-y: auto;
            padding-top: 10px;
            @include dark-scrollbar;
        }

        &-footer {
            padding: 20px 0;
        }

        .text-muted {
            color: rgba(255, 255, 255, .5);
        }
    }
</style>
