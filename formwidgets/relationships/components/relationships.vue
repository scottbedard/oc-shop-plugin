<style lang="scss" scoped>@import 'core';
    .relationships-list {
        padding-bottom: 12px;
    }

    .relationships-item {
        position: relative;

        .form-group {
            padding-bottom: 0;
        }

        .delete-icon {
            color: #ccc;
            outline: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            transition: color $transition-duration $transition-timing-function;

            &:hover {
                color: $red;
                text-decoration: none;
            }
        }
    }
</style>

<template>
    <div class="bedard-shop relationships">
        <div v-for="relationship in relationships" class="relationships-list">
            <div class="relationships-item">
                <v-form-input
                    v-model="relationship.value"
                    ref="relationships"
                    :name="name"
                />
                <a
                    class="delete-icon oc-icon-trash-o"
                    href="#"
                    tabindex="-1"
                    @click.prevent="removeRelationship(relationship)">
                </a>
            </div>
        </div>

        <div class="form-group">
            <v-form-input
                v-model="input"
                :comment="comment"
                :placeholder="placeholder"
                @keydown.tab.prevent="onTab"
                @keypress.enter.prevent="onEnter"
            />
        </div>

        <!-- This field passes our data back to the form widget -->
        <input type="hidden" :name="name" :value="formData" />
    </div>
</template>

<script>
    import trans from 'assets/js/filters/trans/trans';

    export default {
        created() {
            this.relationships = Object.keys(this.value.relationships).map(key => {
                return {
                    value: this.value.relationships[key],
                };
            });
        },
        data() {
            return {
                input: '',
                relationships: [],
            };
        },
        computed: {
            comment() {
                return trans('bedard.shop.api.form.relationships_comment', this.lang);
            },
            isEmpty() {
                return this.trimmedInput.length === 0;
            },
            formattedRelationshipsArray() {
                return this.relationships.map(relationship => {
                    return relationship.value.trim();
                });
            },
            formData() {
                return JSON.stringify({
                    relationships: this.formattedRelationshipsArray,
                });
            },
            placeholder() {
                return trans('bedard.shop.api.form.relationships_placeholder', this.lang);
            },
            transIsEmptyMessage() {
                return trans('bedard.shop.api.form.relationships_is_empty_message', this.lang);
            },
            trimmedInput() {
                return this.input.trim();
            },
        },
        methods: {
            addRelationship() {
                this.relationships.push({
                    value: this.trimmedInput,
                });

                this.input = '';
            },
            focusLastRelationship() {
                if (! this.relationships.length) {
                    // @todo: focus previous form element
                    return;
                }

                let lastInput = this.$refs.relationships[this.relationships.length - 1];

                lastInput.focus();
            },
            onEnter() {
                if (! this.isEmpty) {
                    this.addRelationship();
                }
            },
            onTab(e) {
                if (e.shiftKey) {
                    this.focusLastRelationship();
                } else {
                    this.addRelationship();
                }
            },
            removeRelationship(relationship) {
                this.relationships.splice(this.relationships.indexOf(relationship), 1);
            },
        },
        props: [
            'lang',
            'name',
            'value',
        ],
    };
</script>
