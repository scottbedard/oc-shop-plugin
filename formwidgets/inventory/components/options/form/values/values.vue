<style lang="scss" scoped>@import 'core';
    .list {
        .form-group {
            margin-bottom: 10px;
            padding-bottom: 0;

            /deep/ input {
                padding: 0 30px;
            }
        }

        .value {
            position: relative;
        }

        a {
            color: #ccc;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            transition: color 150ms ease-in-out;
            z-index: 1;

            &:before {
                margin-right: 0;
            }

            &:hover {
                text-decoration: none;
            }
        }

        .oc-icon-bars {
            cursor: move;
            left: 12px;

            &:hover {
                color: $blue;
            }
        }

        .oc-icon-trash-o {
            right: 12px;

            &:hover {
                color: $red;
            }
        }
    }
</style>

<template>
    <div class="form-group text-field is-required">
        <!-- label -->
        <label class="v-required-label" @click="focusNewValue">
            {{ 'bedard.shop.options.form.values' | trans(lang) }}
        </label>

        <!-- list -->
        <div class="list" v-sortable="{ handle: '.oc-icon-bars', onEnd: reorderOptionValue }">
            <div
                v-for="value in values"
                class="value"
                :data-key="value._key"
                :key="value._key">
                <a
                    class="oc-icon-bars"
                    href="#"
                    :title="reorderTitle"
                    @click.prevent>
                </a>
                <v-form-input
                    ref="value"
                    :value="value.name"
                />
                <a
                    class="oc-icon-trash-o"
                    href="#"
                    :title="removeOrRestoreTitle(value)"
                    @click.prevent>
                </a>
            </div>
        </div>

        <!-- create -->
        <v-form-input
            v-model="newValue"
            data-input="new-value"
            ref="newValue"
            :placeholder="placeholder"
            @keydown.tab.prevent="onNewValueTab"
            @keydown.enter.prevent="addValue"
        />
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    import { mapTwoWayState } from 'spyfu-vuex-helpers';
    import Sortable from 'sortablejs';
    import trans from 'assets/js/filters/trans/trans';

    export default {
        computed: {
            ...mapState('inventories', [
                'lang',
            ]),
            ...mapTwoWayState('inventories', {
                'optionForm.data.newValue': 'setOptionFormNewValue',
                'optionForm.data.values': 'setOptionFormValues',
            }),
            placeholder() {
                return trans('bedard.shop.options.form.values_placeholder', this.lang);
            },
            reorderTitle() {
                return trans('bedard.shop.options.form.values_reorder_title', this.lang);
            },
        },
        directives: {
            sortable: {
                inserted: (el, binding) => new Sortable(el, binding.value || {}),
            },
        },
        methods: {
            ...mapActions('inventories', {
                addValue: 'addValueToOption',
                reorderOptionValue: 'reorderOptionValue',
            }),
            removeOrRestoreTitle(value) {
                return value._delete
                    ? trans('bedard.shop.options.form.values_restore_title', this.lang)
                    : trans('bedard.shop.options.form.values_delete_title', this.lang);
            },
            focusNewValue() {
                this.$refs.newValue.focus();
            },
            focusLastValueOrPlaceholder() {
                if (this.$refs.value) {
                    this.$refs.value[this.$refs.value.length - 1].focus();
                } else {
                    this.$emit('focus-placeholder');
                }
            },
            onNewValueTab(e) {
                if (e.shiftKey) {
                    this.focusLastValueOrPlaceholder();
                } else {
                    this.addValue();
                }
            },
        },
    };
</script>
