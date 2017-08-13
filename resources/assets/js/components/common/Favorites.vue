<template>
    <div>
        <h4 class="sidebar-subtitle">{{ $t('favorites.title') }}</h4>
        <div v-if="favorites.length == 0" class="text-muted small">
            {{ $t('favorites.empty') }}
        </div>
        <nav class="nav-list nav-list-small nav-list-light">
            <a @click="showFavorite(favorite)" v-for="favorite in favorites" v-bind:key="favorite.id">
                <i :class="getIcon(favorite)"></i>
                {{ favorite.favoritable.title }}
            </a>
        </nav>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    const typeRouteMapping = {
        topics: 'topic',
        projects: 'project',
    }

    const icons = {
        'projects' : 'icon-stack',
        'topics': 'icon-bubbles'
    }

    export default {
        computed: {
            ...mapGetters({
                favorites: 'favorites',
            }),
        },
        mounted() {
            this.$store.dispatch('getFavorites')
        },
        methods: {
            getIcon(favorite) {
                return icons[favorite.type]
            },
            showFavorite(favorite) {
                if(favorite.type === 'projects' || favorite.type === 'topics') {
                    const name = typeRouteMapping[favorite.type]
                    this.$router.push({name: name, params: {id: favorite.favoritable.id}})
                }
            }
        }
    }
</script>
