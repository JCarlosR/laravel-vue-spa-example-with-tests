<template>
    <card :title="$t('card_title_today')">
        
        <v-button :loading="loadingTasks" class="mb-2">
            {{ $t('btn_add_task') }}
        </v-button>
        
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Task title</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="task in tasks">
                <td>{{ task.title }}</td>
                <td>
                    <a href="" class="btn btn-primary btn-sm">
                        <fa icon="edit" fixed-width/>
                    </a>
                    <a href="" class="btn btn-danger btn-sm">
                        <fa icon="trash" fixed-width/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </card>
</template>

<script>
    export default {
        components: {},
        middleware: 'auth',
        
        data() {
            return {
                tasks: [
                    {
                        id: 1,
                        title: 'Task 1'
                    },
                    {
                        id: 2,
                        title: 'Task 2'
                    },
                    {
                        id: 3,
                        title: 'Task 3'
                    }
                ],
                loadingTasks: false
            };
        },

        metaInfo() {
            return {
                title: this.$t('today')
            };
        }
    }
</script>
