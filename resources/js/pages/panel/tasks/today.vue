<template>
    <task-section :title="$t('card_title_today')" />
</template>

<script>
    import TaskSection from "../../../components/TaskSection";
    
    export default {
        middleware: 'auth',
        
        components: {
            TaskSection
        },
    
        metaInfo() {
            return {
                title: this.$t('today')
            };
        },
    }
</script>
