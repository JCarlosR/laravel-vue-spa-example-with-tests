<template>
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
</template>

<script>
    export default {
        name: 'TaskTable',

        props: {
            tasks: {
                type: Array,
                default: []
            }
        }
    }
</script>
