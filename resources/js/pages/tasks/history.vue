<template>
    <card :title="$t('card_title_history')">
        {{ $t('you_are_logged_in') }}
    </card>
</template>

<script>
    export default {
        components: {},
        middleware: 'auth',

        metaInfo() {
            return {
                title: this.$t('history')
            };
        }
    }
</script>
