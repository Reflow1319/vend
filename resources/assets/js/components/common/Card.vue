<template>
    <div class="card" @click="handleClick" :class="{'text-center': center}" :style="{height: height}">
        <div class="card-title" v-if="$slots['title'] || title">
            <h3 v-if="!$slots['title']">{{ title }}</h3>
            <slot name="title"></slot>
        </div>
        <div class="card-body">
            <slot name="body"></slot>
        </div>
        <div class="card-footer" v-if="$slots['footer']">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
    export default {
      props: {
        title: {
          type: String
        },
        center: {
          type: Boolean,
          default: false
        },
        height: {
          default: 'auto'
        }
      },
      methods: {
        handleClick (e) {
          this.$emit('click', e)
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .card {
        border-radius: 3px;
        background: #fff;
        border: 1px solid #fff;
        cursor: pointer;
        transition: box-shadow .3s ease-in-out, border .3s linear, background .3s linear;
        margin-bottom: 10px;
        display: block;
        text-decoration: none;
        position: relative;
        color: $common;
        @include shadow-sm;
        &:hover, &:focus {
            text-decoration: none;
            background: #fff;
        }
        h3 {
            margin: 0;
        }
        &-list {
            display: flex;
            margin: 0 -10px;
            flex-wrap: wrap;
            .card {
                width: calc(#{percentage(1/3)} - 20px);
                margin: 10px;
            }
        }
        &-body {
            padding: 10px 15px;
        }
        &-title {
            display: block;
            min-height: 50px;
            padding: 10px 15px 0 15px;
        }
        &-lg &-body {
            min-height: 65px;
        }
        &:hover {
            @include shadow;
            border: 1px solid $highlight;
        }
        &-footer {
            display: block;
            padding: 10px 15px;
            a, span {
                display: inline-block;
                margin-right: 5px;
                i {
                    font-size: 14px;
                }
                &:last-child {
                    margin-right: 0;
                }
            }
        }
        &-timer {
            color: #b0bcc3;
            height: 28px;
            vertical-align: top;
            line-height: 28px;
            padding: 0 5px;
            &.active {
                background-color: $green;
                border-radius: 3px;
                color: #FFF;
            }
        }
        &-assigned {
            position: absolute;
            top: 10px;
            right: 15px;
        }
        &-info {
            margin: 0 0 20px 0;
        }
        &-heading {
            color: $common;
            padding: 15px 15px 0 15px;
            min-height: 70px;
        }
        &-users {
            .avatar {
                margin-right: 5px;

            }
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
        }
    }

    .card-assigned {
        margin-bottom: 10px;
    }

    .card-center {
        text-align: center;
        .avatar {
            margin: 5px 0;
        }
    }
</style>