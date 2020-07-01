<template>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>User ID</th>
            <th>E-mail</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="user in users">
            <td>{{ user.id }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>
                <v-button type="primary" small @click.native="$emit('edit', user)">
                    <fa icon="edit" fixed-width/>
                </v-button>
                <v-button type="danger" small @click.native="$emit('delete', user)">
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
        name: 'UserTable',
        components: {VButton},
        props: {
            users: {
                type: Array,
                default: []
            }
        }
    }
</script>
