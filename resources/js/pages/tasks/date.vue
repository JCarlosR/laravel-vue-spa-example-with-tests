<template>
    <card :title="cardTitle">
        
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
    import TaskTable from "../../components/TaskTable";
    import Form from "vform";
    import isValidDate from "../../utils/dates";
    import axios from "axios";
    
    export default {
        components: {
            Modal,
            TaskTable
        },
        middleware: 'auth',

        created() {
            this.cardTitle = `Tasks registered on ${this.$route.params.date}`;
        },
        
        data() {
            return {
                cardTitle: '',
                tasks: [
                ],
                loadingTasks: true,

                form: new Form({
                    title: '',
                    description: '',
                    duration: 5
                })
            };
        },

        metaInfo() {
            return {
                title: this.$t('history')
            };
        },
        
        methods: {
            postTask() {
                console.log('a post request to the server');
            }
        },

        async mounted() {            
            const url = `/api/tasks?date=${this.$route.params.date}`;
            const {data} = await axios.get(url);

            this.tasks = data;
            this.loadingTasks = false;
        },

        beforeRouteEnter(to, from, next) {
            // check if we have a valid date param
            if (isValidDate(to.params.date)) {
                next();
            } else {
                // redirect to other page
                next({
                    name: 'tasks.history',
                    // query: { invalidDate: true }
                });
            }
        }
    }
</script>
