import { createRouter, createWebHistory } from "vue-router";

import home from '../components/HomePage.vue';
import createWebsite from '../components/CreateWebsite.vue';
import createPost from '../components/CreatePost.vue';
import createSubscription from '../components/CreaateSubscription.vue';
import notFoundPage from '../components/NotFoundPage.vue';

const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/website',
        component: createWebsite
    },
    {
        path: '/post',
        component: createPost
    },
    {
        path: '/subscription',
        component: createSubscription
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFoundPage
    }
]

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes
})

export default router
