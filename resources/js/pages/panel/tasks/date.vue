<template>
    <task-section :title="cardTitle" :date="$route.params.date" />
</template>

<script>
    import {isToday, isValidDate} from "../../../utils/dates";
    import TaskSection from "../../../components/TaskSection";

    export default {
        components: {
            TaskSection
        },
        middleware: 'auth',
        
        computed: {
            cardTitle() {
                return `Tasks registered on ${this.$route.params.date}`;  
            } 
        },
        
        metaInfo() {
            return {
                title: this.$t('history')
            };
        },

        beforeRouteEnter(to, from, next) {
            // check if we have a valid date param
            if (!isValidDate(to.params.date)) {
                // redirect to other page
                next({
                    name: 'tasks.history',
                    // query: { invalidDate: true }
                });
            }
            
            // if date === today, redirect to the convenient route
            if (isToday(to.params.date)) {
                next({
                    name: 'tasks.today'
                });
            }

            next();
        }
    }
</script>
