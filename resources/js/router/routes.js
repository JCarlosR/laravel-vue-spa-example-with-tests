function page (path) {
    return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m);
}

export default [
    { path: '/', name: 'welcome', component: page('welcome.vue') },
    
    { path: '/login', name: 'login', component: page('auth/login.vue') },
    { path: '/register', name: 'register', component: page('auth/register.vue') },
    { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
    { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
    { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
    { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },
    
    {
        path: '/tasks',
        component: page('tasks/index.vue'),
        children: [
            { path: '', redirect: { name: 'tasks.today' } },
            { path: 'today', name: 'tasks.today', component: page('tasks/today.vue') },
            { path: 'history', name: 'tasks.history', component: page('tasks/history.vue') },
            { path: 'history/:date', name: 'tasks.date', component: page('tasks/date.vue') },
        ]
    },
    
    { 
        path: '/settings',
        component: page('settings/index.vue'),
        children: [
            { path: '', redirect: { name: 'settings.profile' } },
            { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
            { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
        ] 
    },
    
    { path: '*', component: page('errors/404.vue') }
];