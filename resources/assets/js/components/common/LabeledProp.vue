<template>
    <div class="labeled-prop">
        <div class="text-muted">{{ label }}</div>
        <b>{{ prop }}</b>
    </div>
</template>

<script>
    export default {
      props: {
        label: String,
        prop: String
      }
    }
</script>

<style lang="scss">
    .labeled-prop {
        b {
            display: block;
            font-size: 15px;
        }
    }
</style>

