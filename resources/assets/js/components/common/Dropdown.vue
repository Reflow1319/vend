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
        data() {
            return {
                show: false
            }
        },
        mounted() {
            document.addEventListener('click', this.handleClick, true)
        },
        beforeDestroy() {
            document.removeEventListener('click', this.handleClick, true)
        },
        methods: {
            hide() {
                this.show = false
            },
            open() {
                this.show = true
            },
            handleClick(e) {
                if (!this.$el.contains(e.target)) this.show = false;
            }
        }
    }
</script>
