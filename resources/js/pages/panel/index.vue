<template>
    <div class="row">
        <div class="col-md-3">
            
            <card :title="$t('my_tasks')" class="menu-card">
                <ul class="nav flex-column nav-pills">
                    <li v-for="tab in taskTabs" :key="tab.route" class="nav-item">
                        <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
                            <fa :icon="tab.icon" fixed-width/>
                            {{ tab.name }}
                        </router-link>
                    </li>
                </ul>
            </card>

            <card v-if="['manager', 'admin'].includes(userRole)" :title="$t('management')" class="menu-card my-3">
                <ul class="nav flex-column nav-pills">
                    <li v-for="tab in managementTabs" :key="tab.route" class="nav-item">
                        <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
                            <fa :icon="tab.icon" fixed-width/>
                            {{ tab.name }}
                        </router-link>
                    </li>
                </ul>
            </card>
            
        </div>

        <div class="col-md-9">
            <transition name="fade" mode="out-in">
                <router-view/>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        middleware: 'auth',
        
        created() {
            console.log(this.$store.getters['auth/user']);
        },

        computed: {
            userRole() {
                return this.$store.getters['auth/user'].role;
            },
            
            taskTabs() {
                return [
                    {
                        icon: 'calendar-day',
                        name: this.$t('today'),
                        route: 'tasks.today'
                    },
                    {
                        icon: 'calendar',
                        name: this.$t('history'),
                        route: 'tasks.history'
                    }
                ]
            },
            managementTabs() {
                return [
                    {
                        icon: 'users',
                        name: this.$t('users'),
                        route: 'management.users'
                    }
                ]
            }
        }
    }
</script>
