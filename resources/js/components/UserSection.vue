<template>
    <card :title="title">

        <v-button :loading="loadingUsers" class="mb-2" @click.native="showModal = true">
            {{ $t('btn_add_user') }}
        </v-button>

        <v-modal :title="modalTitle" :has-footer="false" :show="showModal" @close="onCloseModal">
            <form @submit.prevent="onSubmitForm" @keydown="form.onKeydown($event)">
                <alert-success :form="form" :message="status"/>
                
                <p>Note: Users registered internally are automatically e-mail verified.</p>
                
                <!-- Name -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }"
                           class="form-control" type="text" name="name" id="name">
                    <has-error :form="form" field="name"/>
                </div>

                <!-- E-mail -->
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }"
                           class="form-control" type="text" name="email" id="email">
                    <has-error :form="form" field="email"/>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }"
                           class="form-control" type="text" name="password" id="password">
                    <has-error :form="form" field="password"/>
                </div>

                <!-- Role -->
                <div v-if="user.role === 'admin'" class="form-group">
                    <label for="role">Role</label>
                    <select v-model="form.role" name="role" id="role"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                        <option v-for="role in roles">{{ role }}</option>
                    </select>
                    <has-error :form="form" field="role"/>
                </div>

                <v-button :loading="form.busy">
                    Confirm
                </v-button>
            </form>
        </v-modal>

        <p v-if="loadingUsers">Loading users ...</p>

        <user-table v-else-if="users.length > 0" :users="users" @edit="editUser" @delete="deleteUserConfirmation" />

        <p v-else>You haven't registered any user yet.</p>
    </card>
</template>

<script>
    import Modal from "./Modal";
    import Form from "vform";
    import axios from "axios";
    import Swal from 'sweetalert2'
    import UserTable from "./UserTable";

    export default {
        name: 'UserSection',
        props: {
            title: {
                type: String
            },
            date: {
                type: String, // yyyy-mm-dd
                default: ''
            }
        },
        components: {
            UserTable,
            Modal
        },
        

        computed: {
            modalTitle() {
                if (this.editUserId)
                    return 'Edit user #' + this.editUserId;

                return 'New user';
            },
            user() {
                return this.$store.getters['auth/user'];
            }
        },

        data() {
            return {
                showModal: false,
                editUserId: null,
                users: [
                ],
                loadingUsers: true,

                status: '',
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    role: 'user'
                }),

                roles: [
                    'user', 'admin', 'manager'
                ]
            };
        },

        methods: {
            onSubmitForm() {
                if (this.editUserId) {
                    this.updateUser();
                } else {
                    this.postUser();
                }
            },

            async postUser() {
                const url = '/api/users';
                                
                const {data} = await this.form.post(url);

                this.users.push(data);
                this.form.reset();

                this.status = 'The user has been registered successfully.';
            },

            editUser(user) {
                this.editUserId = user.id;

                this.form.fill(user);

                this.showModal = true;
            },

            async updateUser() {
                const {data} = await this.form.put(`/api/users/${this.editUserId}`);

                const userIndex = this.users.findIndex(user => user.id === this.editUserId);

                this.$set(this.users, userIndex, data);

                this.status = 'The user has been updated successfully.';
            },

            deleteUserConfirmation(user) {
                const htmlMessage = `The following user will be deleted: <strong>${user.title}</strong>`;

                Swal.fire({
                    title: 'Are you sure?',
                    html: htmlMessage,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.value) {
                        this.deleteUser(user.id);
                    }
                })
            },

            async deleteUser(userId) {
                const {data} = await axios.delete(`/api/users/${userId}`);

                if (data) {
                    const userIndex = this.users.findIndex(user => user.id === userId);
                    this.$delete(this.users, userIndex);

                    await Swal.fire(
                        'Deleted!',
                        'The selected user has been deleted.',
                        'success'
                    );
                }
            },

            onCloseModal() {
                this.form.reset(); // reset fields
                this.form.clear(); // remove alerts
                
                this.editUserId = null;
                this.showModal = false;
                this.status = '';
            }
        },

        async mounted() {
            let url = '/api/users';
            
            if (this.date) {
                url += `?date=${this.date}`;
            }
            
            const {data} = await axios.get(url);

            this.users = data;
            this.loadingUsers = false;
        }
    }
</script>
