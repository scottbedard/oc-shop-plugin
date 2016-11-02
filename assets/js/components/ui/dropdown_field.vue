<style lang="scss" scoped>@import 'assets/scss/utils';
    .dropdown-container {
        height: 40px;
        position: relative;
    }

    .dropdown-inner {
        background-color: #fff;
        border-radius: 3px;
        position: absolute;
        width: 100%;

        &.is-expanded {
            .selected-text {
                border-bottom: 1px solid #fff !important;
                border-color: #808c8d;
                border-radius: 3px 3px 0 0;
            }
            .expanded-content {
                border-color: #808c8d;
                border-radius: 0 0 3px 3px;
                border-top: 1px solid #fff !important;
            }
        }
    }

    .selected-text {
        align-items: center;
        border: 1px solid #d1d6d9;
        cursor: pointer;
        display: flex;
        height: 40px;
        justify-content: space-between;
        line-height: 1.42857143;
        padding: 0px 23px 0px 11px;

        .is-placeholder {
            color: #ccc;
        }
    }

    .expanded-content {
        background: #fff;
        border-radius: 3px;
        border: 1px solid #d1d6d9;
        padding: 5px 0;
        position: absolute;
        width: 100%;
        z-index: 1;
    }

    .search-container {
        padding: 0px 5px;
        position: relative;

        &:after {
            -webkit-font-smoothing: antialiased;
            color: #95a5a6;
            content: "\f002";
            font-family: FontAwesome;
            font-style: normal;
            font-weight: normal;
            position: absolute;
            right: 20px;
            text-decoration: inherit;
            top: 50%;
            transform: translateY(-50%);
        }

        input {
            appearance: none;
            background-color: #ffffff;
            border-radius: 3px;
            border: 1px solid #e0e0e0;
            color: #555555;
            outline: 0;
            padding: 6px;
            width: 100%;
        }
    }

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    li {
        align-items: center;
        color: #2a3e51;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        margin: 0;
        text-decoration: none;

        &:hover {
            background-color: $light-blue;
            color: #fff;

            .icon-times {
                color: #fff;
            }
        }

        &.is-selected a:not(:hover) {
            background-color: #f5f5f5;
        }

        span {
            flex-grow: 1;
            padding: 6px 11px;
        }


        .icon-times {
            color: #ccc;
            margin-right: 15px;
            padding: 6px;

            &:hover {
                color: $red;
            }
        }
    }
</style>

<template>
    <div :class="{ 'is-required': required }">
        <label v-if="label">{{ label }}</label>
        <div class="dropdown-container" ref="dropdown">
            <div class="dropdown-inner" :class="{ 'is-expanded': isExpanded }">
                <div class="selected-text" @click="onSelectedTextClicked">
                    <span :class="{ 'is-placeholder': isEmpty }">{{ selectedText }}</span>
                    <i :class="{ 'icon-angle-up': isExpanded, 'icon-angle-down': ! isExpanded }"></i>
                </div>
                <div class="expanded-content" v-show="isExpanded">
                    <div v-if="searchable" class="search-container">
                        <input
                            autocapitalize="off"
                            autocomplete="off"
                            autocorred="off"
                            ref="search"
                            role="textbox"
                            spellcheck="false"
                            tabindex="0"
                            type="search"
                            v-model="search"
                            :placeholder="searchPlaceholder"
                            @keydown="onSearchKeypress">
                    </div>
                    <ul>
                        <li v-for="value in searchedValues" :class="{ 'is-selected': selectedValue === value }">
                            <span @click="onValueClicked(value)">
                                {{ value[key] }}
                            </span>
                            <i
                                class="icon-times"
                                v-if="clearable && selectedValue === value"
                                @click="onClearSelectionClicked">
                            </i>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isExpanded: false,
                selectedValue: null,
                search: '',
            };
        },
        computed: {
            isEmpty() {
                return this.selectedValue === null;
            },
            selectedText() {
                return this.isEmpty ? this.placeholder : this.selectedValue[this.key];
            },
            searchedValues() {
                let search = this.search.trim().toLowerCase();

                return this.values.filter(value => {
                    return search.length === 0 ||
                        value[this.key].trim().toLowerCase().indexOf(search) !== -1;
                });
            },
        },
        methods: {
            onClearSelectionClicked() {
                this.selectedValue = null;
            },
            onClickOff(e) {
                // prevent clicks off of our dropdown from closing a popup
                if (! e.path.filter(el => el === this.$refs.dropdown).length &&
                    e.path[0].dataset.bedardShop === 'popup-component') {
                    e.stopPropagation();
                }

                this.isExpanded = false;
            },
            onIsExpandedChanged(isExpanded) {
                document.body.removeEventListener('click', this.onClickOff, true);

                if (isExpanded) {
                    document.body.addEventListener("click", this.onClickOff, true);

                    if (this.searchable) {
                        this.$nextTick(() => this.$refs.search.focus());
                    }
                }
            },
            onSearchKeypress(e) {
                if (e.keyCode === 13 || e.keyCode === 9) {
                    e.preventDefault();

                    if (this.searchedValues.length > 0) {
                        this.selectedValue = this.searchedValues[0];
                    }
                }
            },
            onSelectedTextClicked() {
                this.isExpanded = ! this.isExpanded;
            },
            onSelectedValueChanged(selectedValue) {
                if (selectedValue !== null) {
                    this.search = '';
                    this.isExpanded = false;
                }

                this.$emit('change', selectedValue);
            },
            onValueClicked(value) {
                this.selectedValue = value;
            },
        },
        props: {
            clearable: { default: true, type: Boolean },
            key: { default: 'name', type: String },
            label: { default: null, type: String },
            placeholder: { default: '', type: String },
            required: { default: false, type: Boolean },
            searchable: { default: true, type: Boolean },
            searchPlaceholder: { default: '', type: String },
            values: { default: [], type: Array },
        },
        watch: {
            isExpanded: 'onIsExpandedChanged',
            selectedValue: 'onSelectedValueChanged',
        },
    };
</script>
