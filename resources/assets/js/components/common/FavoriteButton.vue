<template>
    <a @click="toggleFavorite()" :class="[{'highlight': isFavorite.length}, 'btn']">
        <i class="icon icon-star"></i>
    </a>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
      props: ['type', 'id'],
      computed: {
        ...mapGetters({
          favorites: 'favorites'
        }),
        isFavorite () {
          return this.favorites.filter(f => {
            return f.favoritable.id === this.id && f.type === this.type
          })
        }
      },
      methods: {
        toggleFavorite () {
          if (this.isFavorite.length) {
            axios.delete(`favorites/${this.type}/${this.id}`).then(() => {
              this.$store.commit('deleteFavorite', {id: this.isFavorite[0].id})
            })
          } else {
            axios.put(`favorites/${this.type}/${this.id}`).then(res => {
              this.$store.commit('createFavorite', res.data)
            })
          }
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .highlight i {
        color: $warn;
    }
</style>

<style lang="scss">
    @import "../../../sass/variables";

    .highlight i {
        color: $warn;
    }
</style>