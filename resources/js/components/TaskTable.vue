<template>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Task title</th>
            <th>Duration <small>in minutes</small></th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="task in tasks">
            <td>{{ task.title }}</td>
            <td>{{ task.duration }}</td>
            <td>
                <v-button type="primary" small @click.native="$emit('edit', task)">
                    <fa icon="edit" fixed-width/>
                </v-button>
                <v-button type="danger" small @click.native="$emit('delete', task)">
                    <fa icon="trash" fixed-width/>
                </v-button>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import VButton from "./Button";
    export default {
        name: 'TaskTable',
        components: {VButton},
        props: {
            tasks: {
                type: Array,
                default: []
            }
        }
    }
</script>
