<style lang="scss" scoped>@import 'core';
    $statusHeight: 36px;

    .status {
        display: flex;
        border-top: 1px solid #ccc;
        padding-top: 6px;
        margin-top: 6px;
    }

    .status-icon {
        align-items: center;
        display: flex;
        height: $statusHeight;
        justify-content: center;
        width: $statusHeight;
    }

    .status-name {
        justify-content: center;
        display: flex;
        flex-direction: column;

        time {
            font-size: 10px;
        }
    }
</style>

<template>
    <div>
        <!-- Statuses -->
        <div class="statuses">
            <div v-for="status in statuses" class="status">
                <div class="status-icon">
                    <i
                        v-if="status.icon"
                        :class="status.icon"
                        :style="{ color: status.color || null }">
                    </i>
                </div>
                <div class="status-name">
                    <div>{{ status.name | trans(lang) }}</div>
                    <time :datetime="status.pivot.created_at">{{ timestamp(status) }}</time>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        methods: {
            timestamp(status) {
                // @todo: provide a way to customize the format
                return moment(status.pivot.created_at).format('MMMM Do YYYY, h:mm:ssa');
            },
        },
        props: [
            'lang',
            'statuses',
        ],
    };
</script>
