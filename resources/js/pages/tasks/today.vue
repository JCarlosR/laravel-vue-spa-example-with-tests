<template>
    <card :title="$t('card_title_today')">
        
        <v-button :loading="loadingTasks" class="mb-2" data-toggle="modal" data-target="#modalAddTask">
            {{ $t('btn_add_task') }}
        </v-button>
        
        <modal id="modalAddTask" title="New task" :has-footer="false">
            <form @submit.prevent="postTask" @keydown="form.onKeydown($event)">
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
        </modal>
        
        <p v-if="loadingTasks">Loading tasks ...</p>
        
        <task-table v-else-if="tasks.length > 0" :tasks="tasks" />
        
        <p v-else>You haven't registered any task yet.</p>
    </card>
</template>

<script>
    import Modal from "../../components/Modal";
    import Form from "vform";
    import axios from "axios";
    import TaskTable from "../../components/TaskTable";
    
    export default {
        components: {
            TaskTable,
            Modal
        },
        middleware: 'auth',
        
        data() {
            return {
                tasks: [
                ],
                loadingTasks: true,


                form: new Form({
                    title: '',
                    description: '',
                    duration: 5
                }),
            };
        },

        metaInfo() {
            return {
                title: this.$t('today')
            };
        },
        
        methods: {
            postTask() {
                console.log('a post request to the server');
            }
        },
        
        async mounted() {
            const {data} = await axios.get('/api/tasks');
            
            this.tasks = data;
            this.loadingTasks = false;
        }
    }
</script>
