<template>
    <div v-if="currentLog" class="timer" :class="{'active' : running }">
        <div class="timer-left cursor-pointer" @click="toggleTimer(currentLog.card)">
            <div class="timer-toggle"><i :class="running ? 'icon-pause' : 'icon-play'"></i></div>
            <div class="timer-title">
                {{ currentLog.card.title }}
                <b>{{ elapsedFormat(timer, true) }}</b>
            </div>
        </div>
        <div class="timer-right">
            <dropdown :top="true" class="timer-dropdown">
                <div slot="toggle"><i class="icon-chevron-up"></i></div>
                <div slot="content">
                    <div class="dropdown-links">
                        <a @click="toggleTimer(log.card)" v-for="log in currentUser.logs">
                            <span class="label label-primary label-sm pull-right">
                                {{ elapsedFormat(log.length) }}
                            </span>
                            {{ log.card.title }}
                        </a>
                    </div>
                </div>
            </dropdown>
        </div>

    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {elapsedFormat} from '../../utils'
    import Dropdown from '../common/Dropdown.vue'

    export default {
      components: {
        Dropdown
      },
      computed: {
        ...mapGetters({
          currentLog: 'currentLog',
          currentUser: 'currentUser',
          timer: 'timer',
          logs: 'logs'
        }),
        running () {
          return this.currentLog && this.currentLog.is_running
        },
      },
      methods: {
        elapsedFormat,
        toggleTimer (card) {
          this.$store.dispatch('toggleTimer', card)
        }
      }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .timer {
        background: #12B4AF;
        border-radius: 3px;
        color: #FFF;
        display: flex;
        align-items: center;
        font-size: 13px;
        &-left,
        &-right {
            i[class^=icon] {
                color: #fff;
            }
        }
        &-left {
            flex: 1;
            padding: 10px;
            border-right: 1px solid rgba(0, 0, 0, .2);
            display: flex;
            a {
                color: #fff;
            }
            b {
                display: block;
            }
        }
        &-toggle {
            width: 30px;
            height: 30px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, .3);
            text-align: center;
            margin-right: 5px;
            i {
                line-height: 28px;
                font-size: 20px;
            }
        }
        &.active {
            background: $warn;
        }
        &-title {
            flex: 1;
        }
        .dropdown-toggle {
            padding: 10px 8px;
            display: block;
        }
        &-dropdown .dropdown-menu {
            min-width: 230px;
        }
    }

    .log-card-active {
        background: $warn;
        border-radius: 3px;
        padding: 0 5px;
        color: #FFF;
    }
</style>

<style lang="scss">
    @import "../../../sass/variables";

    .timer {
        background: #12B4AF;
        border-radius: 3px;
        color: #FFF;
        display: flex;
        align-items: center;
        font-size: 13px;
        &-left,
        &-right {
            i[class^=icon] {
                color: #fff;
            }
        }
        &-left {
            flex: 1;
            padding: 10px;
            border-right: 1px solid rgba(0, 0, 0, .2);
            display: flex;
            a {
                color: #fff;
            }
            b {
                display: block;
            }
        }
        &-toggle {
            width: 30px;
            height: 30px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, .3);
            text-align: center;
            margin-right: 5px;
            i {
                line-height: 28px;
                font-size: 20px;
            }
        }
        &.active {
            background: $warn;
        }
        &-title {
            flex: 1;
        }
        .dropdown-toggle {
            padding: 10px 8px;
            display: block;
        }
        &-dropdown .dropdown-menu {
            min-width: 230px;
        }
    }

    .log-card-active {
        background: $warn;
        border-radius: 3px;
        padding: 0 5px;
        color: #FFF;
    }
</style>