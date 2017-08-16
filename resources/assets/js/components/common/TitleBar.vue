<template>
    <div class="title-bar">
        <div class="title-bar-title">
            <h3 v-if="!$slots['left']">{{ title }}</h3>
            <slot name="left"></slot>
        </div>
        <div class="title-bar-right">
            <div class="title-bar-tabs" v-if="$slots['tabs']"><slot name="tabs"></slot></div>
            <div class="title-bar-buttons"><slot name="right"></slot></div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
      props: {
        title: String
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .title-bar {
        margin-bottom: 20px;
        background: #fff;
        display: flex;
        padding: 0 20px;
        height: 60px;
        z-index: $zindex-title-bar;
        border-bottom: 1px solid $border-color;
        position: fixed;
        top: 0;
        left: 230px;
        right: 0;
        &-title {
            font-size: 20px;
            margin: 0 15px 0 0;
            line-height: 60px;
            font-weight: normal;
            display: inline-block;
            vertical-align: middle;
        }
        &-label {
            margin: 15px 0 0 0;
        }
        &-right {
            flex: 1;
            text-align: right;
        }
        &-form,
        &-btn {
            margin: 10px 0;
        }
        &-form {
            display: inline-block;
        }
        &-buttons {
            display: inline-block;
            vertical-align: top;
            padding: 10px 0;
        }
        &-arrow {
            color: $text-gray;
            margin: 0 5px;
        }
        &-tabs {
            display: inline-block;
            margin: 0 10px;
            a {
                display: inline-block;
                padding: 0 15px;
                line-height: 58px;
                border-bottom: 2px solid transparent;
                color: $common;
                &.active {
                    font-weight: bold;
                    border-color: $blue;
                }
            }
        }
    }
</style>