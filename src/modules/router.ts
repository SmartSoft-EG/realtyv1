

import { createAuth } from '@websanova/vue-auth'
import { createRouter, createWebHistory } from 'vue-router'
import generatedRoutes from 'virtual:generated-pages'
import { setupLayouts } from 'virtual:generated-layouts'
import driverAuthBearer from '@websanova/vue-auth/dist/drivers/auth/bearer.esm.js'
import driverHttpAxios from '@websanova/vue-auth/dist/drivers/http/axios.1.x.esm.js'
import driverRouterVueRouter from '@websanova/vue-auth/dist/drivers/router/vue-router.2.x.esm.js'
import axios from 'axios'
import { Dialog } from 'vant';

axios.defaults.baseURL = "http://realtyv1.test/api"
axios.defaults.withCredentials = true;

// setup up pages with layouts
const routes = setupLayouts(generatedRoutes)
const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'active-class',
    linkExactActiveClass: 'exact-active-class'

})

const auth = createAuth({
    plugins: {
        http: axios,
        router,
    },
    drivers: {
        http: driverHttpAxios,
        auth: driverAuthBearer,
        router: driverRouterVueRouter,

    },
    options: {
        tokenDefaultKey: 'auth_token',
        stores: ['storage', 'cookie'],
        refreshData: false,
        rolesKey: 'permissions',
        notFoundRedirect: { name: 'user-account' },
    },
})

export const install = (app: any) => {
    app.use(router).use(auth).use(Dialog)
}

