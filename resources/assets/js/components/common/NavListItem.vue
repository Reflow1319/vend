<template>
    <span>
        <router-link :to="{name: to, params: params}" :class="classes" v-if="to" :exact="exact" class="nav-list-item">
            <i :class="'icon-' + icon" v-if="icon"></i>
            {{ label }}
            <span class="badge" v-if="badge">{{ badge }}</span>
        </router-link>
        <a @click="$emit('click', $event)" v-if="!to" :class="classes" class="nav-list-item">
            <i :class="'icon-' + icon" v-if="icon"></i>
            {{ label }}
            <span class="badge" v-if="badge">{{ badge }}</span>
        </a>
    </span>
</template>

<script>
    export default {
      props: {
        icon: String,
        label: String,
        to: String,
        exact: Boolean,
        params: Object,
        active: Boolean,
        classes: Object,
        badge: String
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .nav-list-item {
        padding: 8px 10px;
        border-radius: 3px;
        text-align: left;
        display: block;
        color: $common;
        &.active {
            background: transparentize(#000, .85);
        }
        i {
            margin-right: 5px;
        }
    }
</style>