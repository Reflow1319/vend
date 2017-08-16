<template>
    <div class="media" :class="Classes" @click="$emit('click', $event)">
        <div class="media-left">
            <avatar :image="image" v-if="image"/>
            <slot name="left"/>
        </div>
        <div class="media-body">
            <div class="media-heading">
                <template v-if="!$slots['heading'] && (title || meta)">
                    <b>{{ title }}</b>
                    <span class="text-muted">{{ meta }}</span>
                </template>
                <slot name="heading"></slot>
            </div>
            <div v-html="content" v-if="!$slots['body'] && html"></div>
            <slot name="body"></slot>
        </div>
        <div class="media-right" v-if="$slots['actions']">
            <dropdown :right="true">
                <i class="icon-dots" slot="toggle"></i>
                <ul class="dropdown-links" slot="content">
                    <slot name="actions"></slot>
                </ul>
            </dropdown>
        </div>
    </div>
</template>

<script>
    import Dropdown from './Dropdown'
    import Avatar from './Avatar'

    export default {
      props: {
        title: String,
        image: String,
        meta: String,
        content: String,
        html: Boolean,
        small: Boolean,
        classes: Object
      },
      components: {
        Dropdown,
        Avatar
      },
      computed: {
        Classes () {
          return !this.classes
            ? {'media-sm': this.small}
            : Object.assign(this.classes, {'media-sm': this.small})
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .media {
        border-bottom: 1px solid $light-gray;
        margin: 0;
        padding: 15px;
        display: flex;
        &-center {
            align-items: center;
        }
        &-dark {
            border-color: transparentize(#fff, .9);
            a {
                color: #fff;
            }
        }
        &-left {
            padding-right: 15px;
            position: relative;
        }
        &-body {
            flex: 1;
            .avatar {
                float: left;
                display: block;
                margin-right: 10px;
            }
            p {
                margin: 5px 0;
            }
        }
        &-header a {
            color: $common;
        }
        &-sm {
            padding: 10px 0;
            line-height: 1.3em;
            font-size: 13px;
        }
        &-sm &-left {
            padding-right: 8px;
        }
        &:last-child {
            border: 0;
        }
        &-left-sm {
            min-width: 150px;
        }
        &-left-md {
            min-width: 200px;
        }
        &-left-lg {
            min-width: 250px;
        }
        &-stamp {
            background: $blue;
            color: #FFF;
            text-align: center;
            width: 80px;
            border-radius: 3px;
            padding: 10px;
            min-height: 60px;
            font-size: 12px;
            &-warn {
                background: $warn;
            }
            &-success {
                background: $green;
            }
            b {
                font-size: 20px;
                display: block;
            }
        }
        &-image {
            max-width: 40px;
            border-radius: 3px;
            display: inline-block;
            vertical-align: top;
        }
    }
</style>