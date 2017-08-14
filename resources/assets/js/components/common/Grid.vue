<template>
    <div class="grid">
        <div :class="'col-md-' + (12 / size)" v-for="(child, index) in $childs">
            {{ $childs[index] }}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            size: {
                type: Number,
                default: 4
            }
        }
    }
</script>