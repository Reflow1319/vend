require('./bootstrap');
window.Vue = require('vue');

import moment from 'moment'
import {mapGetters} from 'vuex'
import store from './stores/store'
import MainNav from './components/common/MainNav.vue'
import Modal from './components/common/Modal.vue'
import mixins from './mixins'
import router from './routes'


Vue.mixin(mixins)

import i18n from './localization'

const app = new Vue({
    router,
    store,
    i18n,
    el: '#app',
    data() {
        return {
            modalVisible: false
        }
    },
    computed: {
        ...mapGetters({
            currentUser: 'currentUser'
        })
    },
    methods: {
        getUser(user) {
            if (user && user.role !== 'client') {
                this.$store.dispatch('getUsers')
            }
        }
    },
    components: {
        MainNav,
        Modal,
    },
    mounted() {
        moment.locale('en');

        this.$store.dispatch('getNotifications').then(() => {
            this.$root.$emit('notifications:fetched')
        })

        this.$store.dispatch('getCurrentUser')
    },
    watch: {
        'currentUser': 'getUser'
    }
});
