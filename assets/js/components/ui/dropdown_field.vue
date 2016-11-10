<style lang="scss" scoped>@import 'assets/scss/utils';
    .dropdown-outer {
        background-color: #fff;
        display: block;
        font-size: 14px;
        line-height: 1.42857143;
        position: relative;
        width: 100%;

        .dropdown-inner {
            border: 1px solid #d1d6d9;
            border-radius: 3px;
        }

        &.is-expanded {
            .dropdown-inner {
                border-bottom: 1px solid transparent;
                border-radius: 3px 3px 0 0;
            }

            .options {
                border-radius: 0 0 3px 3px;
                border-top: 1px solid transparent;
            }
        }
    }


    .value {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        height: 38px;
        padding: 8px 13px 9px;

        span:before { margin-right: 0 }
        span.is-placeholder { color: #ccc }
    }

    .options {
        background-color: #fff;
        border-top: 0;
        box-shadow: 0 3px 6px rgba(0,0,0,0.075);
        border: 1px solid #d1d6d9;
        left: 0;
        position: absolute;
        top: 100%;
        width: 100%;
        z-index: 1;
    }

    .search {
        padding: 0 4px 4px;
        position: relative;

        input {
            border-radius: 3px;
            border: 1px solid #d1d6d9;
            color: #385487;
            outline: 0;
            padding: 4px;
            font-size: 14px;
            width: 100%;
        }

        .icon-search {
            color: #95a5a6;
            position: absolute;
            right: 10px;
            top: 8px;
        }
    }

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    li {
        display: flex;
        cursor: pointer;
        justify-content: space-between;
        padding: 6px;
        user-select: none;

        &.is-selected {
            background-color: #f5f5f5;
            a { display: inline }
        }

        &:hover {
            background-color: #4da7e8;
            color: #fff;
            a { color: #fff }
        }

        a {
            color: #aaa;
            display: none;
            text-decoration: none;
            &:before { margin-right: 5px }
            &:hover { color: $red }
        }
    }

    p {
        margin: 0;
        padding: 6px;
    }
</style>

<template>
    <div class="form-group" :class="{
        'is-required': required,
        'span-full': span === 'full',
        'span-left': span === 'left',
        'span-right': span === 'right',
    }">
        <label v-if="label">{{ label }}</label>
        <div class="dropdown-outer" :class="{ 'is-expanded': isExpanded }" ref="dropdown">
            <div class="dropdown-inner">
                <div class="value" @click="onValueClicked">
                    <span :class="{ 'is-placeholder': value === null }">{{ selectedValue }}</span>
                    <span :class="{
                        'oc-icon-angle-down': ! isExpanded,
                        'oc-icon-angle-up': isExpanded,
                    }"></span>
                </div>
                <div class="options" v-if="isExpanded">
                    <div class="search" v-if="searchable">
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
                            @keydown="onSearchKeydown">
                        <i class="icon-search"></i>
                    </div>
                    <ul v-if="filteredOptions.length > 0">
                        <li
                            v-for="option in filteredOptions"
                            :class="{ 'is-selected': option === value }"
                            @click="onOptionClicked(option)">
                            <span>{{ option[display] }}</span>
                            <a
                                class="oc-icon-times"
                                href="#"
                                @click.prevent.stop="onClearSelectionClicked">
                            </a>
                        </li>
                    </ul>
                    <p v-else>
                        {{ emptyMessage }}
                    </p>
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
                search: '',
            };
        },
        computed: {
            filteredOptions() {
                let search = this.search.trim().toLowerCase();

                return this.options.filter(option => {
                    return search.length === 0 ||
                        option[this.display].trim().toLowerCase().indexOf(search) !== -1;
                });
            },
            selectedValue() {
                return this.value ? this.value[this.display] : this.placeholder;
            },
        },
        methods: {
            onClearSelectionClicked() {
                this.selectOption(null);
            },
            onClickOff(e) {
                // prevent clicks off of our dropdown from closing a popup
                if (! e.path.filter(el => el === this.$refs.dropdown).length &&
                    e.path[0].dataset.bedardShop === 'popup-component') {
                    e.stopPropagation();
                }

                if (! e.path.filter(el => el === this.$refs.dropdown).length) {
                    this.isExpanded = false;
                }
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
            onOptionClicked(option) {
                this.selectOption(option);
            },
            onSearchKeydown(e) {
                if (e.keyCode === 13 || e.keyCode === 9) {
                    e.preventDefault();

                    if (this.filteredOptions.length > 0) {
                        this.selectOption(this.filteredOptions[0]);
                    }
                }
            },
            onValueClicked() {
                this.isExpanded = ! this.isExpanded;
            },
            selectOption(option) {
                this.$emit('change', option, this.options);
                this.isExpanded = false;
            },
        },
        props: {
            display: { default: 'name', type: String },
            emptyMessage: { default: 'sdf', type: String },
            label: { default: null },
            options: { default : [], type: Array },
            placeholder: { default: '', type: String },
            searchable: { default: true, type: Boolean },
            searchPlaceholder: { default: '', type: String },
            span: { default: 'full', type: String },
            required: { default: false, type: Boolean },
            value: { default: null },
        },
        watch: {
            isExpanded: 'onIsExpandedChanged',
        },
    };
</script>
