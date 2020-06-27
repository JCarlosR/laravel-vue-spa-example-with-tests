<template>
    <card :title="$t('your_info')">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
            <alert-success :form="form" :message="$t('info_updated')"/>

            <!-- Name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
                <div class="col-md-7">
                    <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                           type="text" name="name">
                    <has-error :form="form" field="name"/>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                <div class="col-md-7">
                    <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control"
                           type="email" name="email">
                    <has-error :form="form" field="email"/>
                </div>
            </div>

            <!-- Working hours -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('working_hours') }}</label>
                <div class="col-md-7">
                    <input v-model="form.working_hours" :class="{ 'is-invalid': form.errors.has('working_hours') }" class="form-control"
                           type="number" name="working_hours" min="1" step="1" max="10">
                    <has-error :form="form" field="working_hours"/>
                    <p class="text-muted">{{ $t('working_hours_hint') }}</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-9 ml-md-auto">
                    <v-button :loading="form.busy" type="success">
                        {{ $t('update') }}
                    </v-button>
                </div>
            </div>
        </form>
    </card>
</template>

<script>
    import Form from 'vform'
    import {mapGetters} from 'vuex'

    export default {
        scrollToTop: false,

        metaInfo() {
            return {title: this.$t('settings')}
        },

        data: () => ({
            form: new Form({
                name: '',
                email: '',
                working_hours: ''
            })
        }),

        computed: mapGetters({
            user: 'auth/user'
        }),

        created() {
            // Fill the form with user data.
            this.form.keys().forEach(key => {
                this.form[key] = this.user[key]
            })
        },

        methods: {
            async update() {
                const {data} = await this.form.patch('/api/settings/profile');

                this.$store.dispatch('auth/updateUser', {user: data});
            }
        }
    }
</script>
