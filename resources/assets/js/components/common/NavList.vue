<template>
    <div class="nav-list" :class="{'nav-list-light': light, 'nav-list-small': small}">
        <h4 v-if="title" class="nav-list-title">{{ title }}</h4>
        <nav>
            <slot/>
        </nav>
    </div>
</template>

<script>
    export default {
        props: {
            light: Boolean,
            title: String,
            small: Boolean
        }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .nav-list {
        margin: 0 0 30px 0;
        &-title {
            text-transform: uppercase;
            font-size: 13px;
            margin: 10px 0 5px 0;
            opacity: .6;
        }
        &-light, &-light a {
            color: #fff;
        }
        &-small, &-small i {
            font-size: 13px;
        }
        .badge {
            float: right;
        }
    }
</style>