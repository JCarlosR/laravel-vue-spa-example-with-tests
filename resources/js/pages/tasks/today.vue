<template>
    <card :title="$t('card_title_today')">
        
        <v-button :loading="loadingTasks" class="mb-2" @click.native="showModal = true">
            {{ $t('btn_add_task') }}
        </v-button>
        
        <v-modal :title="modalTitle" :has-footer="false" :show="showModal" @close="onCloseModal">
            <form @submit.prevent="postTask" @keydown="form.onKeydown($event)">
                <alert-success :form="form" :message="status"/>
                
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
        
        <task-table v-else-if="tasks.length > 0" :tasks="tasks" @edit="editTask" />
        
        <p v-else>You haven't registered any task yet.</p>
    </card>
</template>

<script>
    import Modal from "../../components/Modal";
    import TaskTable from "../../components/TaskTable";
    import Form from "vform";
    import axios from "axios";
    
    export default {
        components: {
            TaskTable,
            Modal
        },
        middleware: 'auth',
        
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
                    duration: 5
                })
            };
        },

        metaInfo() {
            return {
                title: this.$t('today')
            };
        },
        
        methods: {
            async postTask() {
                const {data} = await this.form.post('/api/tasks');
                
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
                
            },
            
            onCloseModal() {
                this.form.reset();
                this.editTaskId = null;
                
                this.showModal = false;
            }
        },
        
        async mounted() {
            const {data} = await axios.get('/api/tasks');
            
            this.tasks = data;
            this.loadingTasks = false;
        }
    }
</script>
