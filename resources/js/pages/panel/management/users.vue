<template>
    <user-section :title="$t('users')" />
</template>

<script>    
    import UserSection from "../../../components/UserSection";

    export default {
        middleware: 'auth',
        
        components: {
            UserSection
        },
    
        metaInfo() {
            return {
                title: this.$t('users')
            };
        },
    }
</script>
