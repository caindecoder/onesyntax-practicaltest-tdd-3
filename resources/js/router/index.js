import { createRouter, createWebHistory } from "vue-router";

import home from '../views/homePage/HomePage.vue';
import createWebsite from '../views/createWebsite/CreateWebsite.vue';
import createPost from '../views/createPost/CreatePost.vue';
import createSubscription from '../views/createSubscription/CreateSubscription.vue';
import notFoundPage from '../views/notFoundPage/NotFoundPage.vue';

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
