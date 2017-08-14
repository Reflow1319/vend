<template>
    <div class="form-group">
        <label v-if="label">{{ $t(label) }}</label>
        <dropdown ref="dropdown">
            <template slot="toggle">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           :value="date"
                           :placeholder="placeholder"
                           @focus="show()">
                    <input type="text"
                           class="form-control"
                           :value="time"
                           placeholder="HH:MM"
                           @change="setTime"
                           v-if="hasTime">
                    <a class="btn btn-default" @click="clear()"><i class="icon-delete"></i></a>
                </div>
            </template>
            <div class="datepicker" slot="content"></div>
        </dropdown>
    </div>
</template>

<script>
    import moment from 'moment'
    import datepicker from 'jquery-ui/ui/widgets/datepicker'
    import Dropdown from '../common/Dropdown.vue'

    export default {
        name: 'Datepicker',
        components: {
            Dropdown
        },
        props: {
            value: {},
            placeholder: {},
            hasTime: {},
            label: ''
        },
        data() {
            return {
                innerDate: null,
                innerTime: null,
                showDatepicker: false
            }
        },
        computed: {
            date() {
                return this.innerDate ? moment(this.innerDate).format('MMM D, YYYY') : ''
            },
            time() {
                return this.innerTime ? this.innerTime : ''
            }
        },
        mounted() {
            if (this.value) {
                let date = this.value.split(' ');
                this.innerDate = date[0]
                this.innerTime = this.hasTime ? date[1] : null
            }

            $(this.$el).find('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                onSelect: dateText => {
                    this.innerDate = dateText
                    this.change()
                    this.$refs.dropdown.hide()
                }
            })
        },
        methods: {
            setTime(e) {
                if (e.target.value !== ''
                    && e.target.value.length === 5
                    && e.target.value.indexOf(':')
                ) {
                    this.innerTime = e.target.value
                    this.change()
                }
            },
            change() {
                this.$emit('input', this.innerDate + (this.hasTime ? ' ' + this.innerTime : ''))
                this.showDatepicker = false
            },
            show() {
                this.$refs.dropdown.open()
            },
            clear() {
                this.innerTime = this.innerDate = null
                this.$emit('input', null)
                this.showDatepicker = false
            }
        }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .ui-datepicker {
        padding: 0 10px 10px 10px;
        font-size: 13px;
    }

    .ui-datepicker-calendar {

        td {
            text-align: center;

            a {
                color: $common;
                display: block;
                padding: 3px 5px;
                border-radius: 3px;
            }

            &.ui-datepicker-today a {
                background: $warn;
                color: #FFF;
            }

        }
    }

    .ui-datepicker-header {
        margin-bottom: 10px;
        padding-bottom: 5px;

        &:before,
        &:after {
            clear: both;
            content: '';
            display: table;
        }

    }

    .ui-datepicker-next,
    .ui-datepicker-prev {
        position: absolute;
        top: 5px;
        display: block;
        padding: 5px 10px;
        cursor: pointer;
    }

    .ui-datepicker-current-day a {
        background: $highlight;
        color: #FFF;
    }

    .ui-datepicker-prev {
        left: 0;
    }

    .ui-datepicker-next {
        right: 0;
    }

    .ui-datepicker-title {
        padding: 5px 5px 10px 5px;
        text-align: center;
        border-bottom: 1px solid $light-gray;
    }

    .ui-datepicker-next {
        float: right;
    }
</style>