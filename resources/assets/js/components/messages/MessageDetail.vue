<template>
    <modal-content>
        <message-item :message="message"></message-item>
    </modal-content>
</template>

<script>
    import {mapGetters} from 'vuex'
    import MessageItem from './MessageItem.vue'
    import ModalContent from '../common/ModalContent.vue'

    export default {
      components: {
        MessageItem,
        ModalContent
      },
      computed: {
        ...mapGetters({
          message: 'message'
        })
      }
    }
</script>