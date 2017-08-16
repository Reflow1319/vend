<template>
    <div class="dropdown"
         :class="[{'open': show, 'dropdown-right pull-right': right, 'dropdown-inline': inline, 'dropdown-top': top}, classes]">
        <a @click="open()" class="dropdown-toggle">
            <slot name="toggle"></slot>
        </a>
        <div class="dropdown-menu" @click.stop>
            <slot name="content"></slot>
        </div>
    </div>
</template>

<script>
    export default {
      props: ['right', 'inline', 'top', 'classes'],
      data () {
        return {
          show: false
        }
      },
      mounted () {
        document.addEventListener('click', this.handleClick, true)
      },
      beforeDestroy () {
        document.removeEventListener('click', this.handleClick, true)
      },
      methods: {
        hide () {
          this.show = false
        },
        open () {
          this.show = true
        },
        handleClick (e) {
          if (!this.$el.contains(e.target)) this.show = false
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .dropdown {
        position: relative;
        &-inline {
            display: inline-block;
        }
        &-title {
            background: $light-gray;
            font-weight: bold;
            padding: 5px 10px;
            a {
                color: #444;
            }
        }
        &-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            padding: 5px 0;
            background: #FFF;
            border-radius: 3px;
            text-align: left;
            min-width: 160px;
            z-index: $zindex-dropdown;
            @include shadow;
        }
        &-top &-menu {
            top: auto;
            bottom: 100%;
        }
        &-body {
            padding: 15px;
        }
        &-links {
            list-style-type: none;
            padding: 0;
            margin: 0;
            a {
                display: block;
                padding: $padding-base;
                color: $common;
                &:hover {
                    background: $light-gray;
                }
            }
        }
        &-up {
            .dropdown-menu {
                bottom: 100%;
                top: auto;
            }
        }
        &-right .dropdown-menu {
            right: 0;
            left: auto;
        }
        &.open > .dropdown-menu {
            display: block;
        }
    }
</style>

<style lang="scss">
    @import "../../../sass/variables";

    .dropdown {
        position: relative;
        &-inline {
            display: inline-block;
        }
        &-title {
            background: $light-gray;
            font-weight: bold;
            padding: 5px 10px;
            a {
                color: #444;
            }
        }
        &-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            padding: 5px 0;
            background: #FFF;
            border-radius: 3px;
            text-align: left;
            min-width: 160px;
            z-index: $zindex-dropdown;
            @include shadow;
        }
        &-top &-menu {
            top: auto;
            bottom: 100%;
        }
        &-body {
            padding: 15px;
        }
        &-links {
            list-style-type: none;
            padding: 0;
            margin: 0;
            a {
                display: block;
                padding: $padding-base;
                color: $common;
                &:hover {
                    background: $light-gray;
                }
            }
        }
        &-up {
            .dropdown-menu {
                bottom: 100%;
                top: auto;
            }
        }
        &-right .dropdown-menu {
            right: 0;
            left: auto;
        }
        &.open > .dropdown-menu {
            display: block;
        }
    }
</style>