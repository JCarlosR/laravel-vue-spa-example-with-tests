<template>
    <card :title="title">

        <v-button :loading="loadingTasks" class="mb-2" @click.native="showModal = true">
            {{ $t('btn_add_task') }}
        </v-button>

        <v-modal :title="modalTitle" :has-footer="false" :show="showModal" @close="onCloseModal">
            <form @submit.prevent="onSubmitForm" @keydown="form.onKeydown($event)">
                <alert-success :form="form" :message="status"/>

                <!-- Date (optional; default is today) -->
                <input v-if="this.date" v-model="form.date" type="hidden" name="date">
                
                <!-- Title -->
                <div class="form-group">
                    <label for="title">Task title</label>
                    <input v-model="form.title" :class="{ 'is-invalid': form.errors.has('title') }"
                           class="form-control" type="text" name="title" id="title">
                    <has-error :form="form" field="title"/>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Task description</label>
                    <textarea name="description" id="description" rows="2" class="form-control" v-model="form.description"
                              :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                    <has-error :form="form" field="description"/>
                </div>

                <!-- Duration -->
                <div class="form-group">
                    <label for="duration">Duration <small>(in minutes)</small></label>
                    <input v-model="form.duration" :class="{ 'is-invalid': form.errors.has('duration') }"
                           placeholder="How long did it take?"
                           class="form-control" type="number" step="1" name="duration" id="duration">
                    <has-error :form="form" field="duration"/>
                </div>

                <v-button :loading="form.busy">
                    Confirm
                </v-button>
            </form>
        </v-modal>

        <p v-if="loadingTasks">Loading tasks ...</p>

        <task-table v-else-if="tasks.length > 0" :tasks="tasks" @edit="editTask" @delete="deleteTaskConfirmation" />

        <p v-else>You haven't registered any task yet.</p>
    </card>
</template>

<script>
    import Modal from "./Modal";
    import TaskTable from "./TaskTable";
    import Form from "vform";
    import axios from "axios";
    import Swal from 'sweetalert2'

    export default {
        name: 'TaskDate',
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
            TaskTable,
            Modal
        },

        computed: {
            modalTitle() {
                if (this.editTaskId)
                    return 'Edit task #' + this.editTaskId;

                return 'New task';
            }
        },

        data() {
            return {
                showModal: false,
                editTaskId: null,
                tasks: [
                ],
                loadingTasks: true,

                status: '',
                form: new Form({
                    title: '',
                    description: '',
                    duration: 5,
                    date: this.date
                })
            };
        },

        methods: {
            onSubmitForm() {
                if (this.editTaskId) {
                    this.updateTask();
                } else {
                    this.postTask();
                }
            },

            async postTask() {
                const url = '/api/tasks';
                                
                const {data} = await this.form.post(url);

                this.tasks.push(data);
                this.form.reset();

                this.status = 'The task has been registered successfully.';
            },

            editTask(task) {
                this.editTaskId = task.id;

                this.form.fill(task);

                this.showModal = true;
            },

            async updateTask() {
                const {data} = await this.form.put(`/api/tasks/${this.editTaskId}`);

                const taskIndex = this.tasks.findIndex(task => task.id === this.editTaskId);

                this.$set(this.tasks, taskIndex, data);

                this.status = 'The task has been updated successfully.';
            },

            deleteTaskConfirmation(task) {
                const htmlMessage = `The following task will be deleted: <strong>${task.title}</strong>`;

                Swal.fire({
                    title: 'Are you sure?',
                    html: htmlMessage,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.value) {
                        this.deleteTask(task.id);
                    }
                })
            },

            async deleteTask(taskId) {
                const {data} = await axios.delete(`/api/tasks/${taskId}`);

                if (data) {
                    const taskIndex = this.tasks.findIndex(task => task.id === taskId);
                    this.$delete(this.tasks, taskIndex);

                    await Swal.fire(
                        'Deleted!',
                        'The selected task has been deleted.',
                        'success'
                    );
                }
            },

            onCloseModal() {
                this.form.reset(); // reset fields
                this.form.clear(); // remove alerts
                
                this.editTaskId = null;
                this.showModal = false;
                this.status = '';
            }
        },

        async mounted() {
            let url = '/api/tasks';
            
            if (this.date) {
                url += `?date=${this.date}`;
            }
            
            const {data} = await axios.get(url);

            this.tasks = data;
            this.loadingTasks = false;
        }
    }
</script>
