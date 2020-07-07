<template>
    <div>
        <div class="top-right links">
            <template v-if="authenticated">
                <router-link :to="{ name: 'tasks.today' }">
                    {{ $t('my_tasks') }}
                </router-link>
            </template>
            <template v-else>
                <router-link :to="{ name: 'login' }">
                    {{ $t('login') }}
                </router-link>
                <router-link :to="{ name: 'register' }">
                    {{ $t('register') }}
                </router-link>
            </template>
        </div>

        <div class="text-center">
            <div class="title mb-4">
                {{ title }}
            </div>

            <div class="links">
                <a href="https://git.toptal.com/screening/juan-carlos-ramos-suyon-2" target="_blank">
                    Project Repository
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        layout: 'basic',

        metaInfo() {
            return {
                title: this.$t('welcome')
            }
        },

        data: () => ({
            title: window.config.appName
        }),

        computed: mapGetters({
            authenticated: 'auth/check'
        })
    }
</script>

<style scoped>
    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .title {
        font-size: 85px;
    }
</style>
