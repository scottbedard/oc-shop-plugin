<style lang="scss" scoped>@import 'core';
    .list {
        .form-group {
            padding-bottom: 0;

            /deep/ input {
                padding: 0 32px;
            }

            &:after {
                // we are using this pseudo element because a margin-bottom
                // causes the sortable library to lose it's mind. keeping
                // the spacing within our border prevents various bugs.
                display: block;
                height: 10px;
                content: "";
            }
        }

        .value {
            position: relative;
        }

        a {
            color: #ccc;
            outline: none;
            position: absolute;
            top: 10px;
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
            left: 12px;
        }

        &:not(.is-reordering) .oc-icon-bars {
            cursor: move;
        }

        .oc-icon-trash-o,
        .oc-icon-undo {
            right: 12px;
        }

        .oc-icon-trash-o:hover {
            color: $red;
        }

        .oc-icon-undo:hover {
            color: $green;
        }
    }

    .sortable-ghost {
        opacity: 0;
    }
</style>

<template>
    <div class="form-group text-field is-required">
        <!-- label -->
        <label class="v-required-label" @click="focusNewValue">
            {{ 'bedard.shop.options.form.values' | trans(lang) }}
        </label>

        <!-- list -->
        <div v-sortable="sortableOptions" class="list" :class="isReordering && 'is-reordering'">
            <div
                v-for="value in values"
                class="value"
                :data-key="value._key"
                :key="value._key">
                <a
                    class="oc-icon-bars"
                    href="#"
                    tabindex="-1"
                    :title="reorderTitle"
                    @click.prevent>
                </a>
                <v-form-input
                    data-input="option-value"
                    ref="value"
                    :value="value.name"
                    @input="updateValue({ key: value._key, value: $event })"
                />
                <a
                    data-action="delete"
                    href="#"
                    tabindex="-1"
                    :class="{
                        'oc-icon-trash-o': !value._delete,
                        'oc-icon-undo': value._delete,
                    }"
                    :title="removeOrRestoreTitle(value)"
                    @click.prevent="toggleValueDelete(value)">
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
    import trans from 'assets/js/filters/trans/trans';

    export default {
        computed: {
            ...mapState('inventories', [
                'lang',
            ]),
            ...mapTwoWayState('inventories', {
                'optionForm.newValue': 'setOptionFormNewValue',
                'optionForm.data.values': 'setOptionFormValues',
                'optionForm.isReordering': 'setOptionFormIsReordering',
            }),
            placeholder() {
                return trans('bedard.shop.options.form.values_placeholder', this.lang);
            },
            reorderTitle() {
                return trans('bedard.shop.options.form.values_reorder_title', this.lang);
            },
            sortableOptions() {
                return {
                    animation: 100,
                    handle: '.oc-icon-bars',
                    onEnd: this.onReorderEnd,
                    onStart: this.onReorderStart,
                };
            },
        },
        methods: {
            ...mapActions('inventories', {
                addValue: 'addValueToOption',
                toggleValueDelete: 'toggleOptionValueDelete',
                updateValue: 'updateOptionValue',
            }),
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
            onReorderStart() {
                this.isReordering = true;
            },
            onReorderEnd(indices) {
                this.isReordering = false;
                this.$store.dispatch('inventories/reorderOptionValue', indices);
            },
            removeOrRestoreTitle(value) {
                return value._delete
                    ? trans('bedard.shop.options.form.values_restore_title', this.lang)
                    : trans('bedard.shop.options.form.values_delete_title', this.lang);
            },
        },
    };
</script>
