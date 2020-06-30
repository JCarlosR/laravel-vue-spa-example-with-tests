<template>
    <task-date :title="$t('card_title_today')" />
</template>

<script>
    import TaskDate from "../../components/TaskDate";
    
    export default {
        middleware: 'auth',
        
        components: {
            TaskDate
        },
    
        metaInfo() {
            return {
                title: this.$t('today')
            };
        },
    }
</script>
