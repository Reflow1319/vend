<template>
    <div>
        <div class="modal-header">
            <div class="modal-header-left">
                <a v-if="left" @click="left.action()">{{ $t(left.label) }}</a>
            </div>
            <div class="modal-title">
                {{ $t(title) }}
            </div>
            <div class="modal-header-right">
                <a v-if="right" @click="right.action()">{{ $t(right.label) }}</a>
            </div>
            <div class="modal-meta" v-if="$slots['modal-meta']">
                <slot name="modal-meta"></slot>
            </div>
        </div>
        <div class="modal-body">
            <slot></slot>
        </div>
        <div class="modal-footer" v-if="cancel || save">
            <a @click="cancel()" class="btn btn-default" v-if="cancel">{{ $t('common.cancel') }}</a>
            <a @click="save()" class="btn btn-primary" v-if="save">{{ $t('common.save') }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['actions', 'title', 'left', 'right', 'save', 'cancel'],
    }
</script>