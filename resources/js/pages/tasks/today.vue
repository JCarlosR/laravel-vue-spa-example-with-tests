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
    import Modal from "../../components/Modal";
    import Form from "vform";
    
    export default {
        components: {
            Modal
        },
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
                loadingTasks: false,


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
        }
    }
</script>
