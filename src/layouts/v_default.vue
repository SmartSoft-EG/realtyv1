<script setup lang="ts">
import { useAuth } from '@websanova/vue-auth'
import nestedGroupby from 'nested-groupby'

import axios from 'axios'
import routes from '~pages'
import { useNotifyStore } from '@/stores/notification'
const $auth = useAuth()
const notify = useNotifyStore()
const isDrawerOpen = ref<Boolean>(null)
const visible_routes = computed(() => routes.filter(r => (r.meta && !r.meta.hide) || !r.meta))
const public_routes = computed(() => visible_routes.value.filter(r => !r.meta || (r.meta && !r.meta.auth)))
const auth_routes = computed(() => visible_routes.value.filter(r => (r.meta && r.meta.auth == true) || (r.meta && r.meta.auth && $auth.check(r.meta.auth.roles))))
const grouped_auth_routes = computed(() => nestedGroupby(auth_routes.value, ['meta.group']))

onMounted(() => {
    const lang: string[] = []
    axios.interceptors.response.use((res) => {
        if (res.data.success)
            notify.showMessage(res.data.success, 'success')
        notify.stopLoading()
        return res
    },
        (err) => {
            const result = err.response.data
            notify.stopLoading()
            notify.showMessage(
                lang[result.data]
                || lang[result.code]
                || result.message
                || result.error
                || result.errors[Object.keys(result.errors)[0]][0],
                'errors',
            )
            throw err
        },
    )

    axios.interceptors.request.use(
        (config) => {
            notify.startLoading()
            return config
        },
        (err) => {
            notify.stopLoading()
            return Promise.reject(err)
        },
    )

})

function logout() {
    axios.post('auth/logout').then(() => {
        $auth.logout()
    })
}
</script>

<template>
    <v-app>
        <v-app-bar class="bg-grey" :priority="1" flat app height="60" clipped-left>
            <v-app-bar-nav-icon @click="isDrawerOpen = !isDrawerOpen" />
            <v-toolbar-title>Africa Health ExoCon 2022</v-toolbar-title>
            <v-spacer />
            <!--<q-btn @click="$auth.logout()">logout</q-btn>
<q-btn @click="$vuetify.rtl = true">rtl</q-btn> -->
            <!-- v-progress-linear(v-show="notify.loading" :indeterminate="true" height="3" bottom absolute color="primary") -->
            <v-progress-circular v-show="notify.loading" :indeterminate="true" color="white" />
        </v-app-bar>

        <v-navigation-drawer class="bg-sky-50" v-model="isDrawerOpen" app flat :right="$vuetify.rtl">
            <div v-if="$auth.check()" class="px-1 flex align-center bg-sky-200 white-text h-15">
                <v-avatar size="20">
                    <img :src="$storage + $auth.user().img" alt>
                </v-avatar>

                <h5 class="text-center ma-2 white--text">
                    {{ $auth.user().name.split(/(\s).+\s/).join("") }}
                </h5>

                <v-spacer />
                <q-btn icon="mdi-power" density="compact" @click="logout()" />
            </div>
            <v-list class="bg-sky-50" nav density="compact">
                <!-- auth routes --><template v-if="$auth.check()">
                    <template v-for="(group, name) in grouped_auth_routes" :key="name">
                        <v-list-subheader class="blue" style="color: blue;">
                            {{ name }}
                        </v-list-subheader>
                        <v-list-item v-for="(route, i) in group" :key="route.path" class="ml-3" :to="route.path">
                            <v-list-item-title>{{ route.name }}</v-list-item-title>
                        </v-list-item>
                        <v-divider />
                    </template>
                </template>
                <v-list-subheader>Genral</v-list-subheader><!-- public routes --><template v-for="r2 in public_routes"
                    :key="r2.path">
                    <v-list-item :to="r2.path">
                        <v-list-item-title>{{ r2.name }}</v-list-item-title>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-main class="h-auto">
            <v-container fluid>
                <router-view />
            </v-container>
        </v-main><!--  v-footer.px-0.py-3.bg-light-400(height="400") -->
        <!--      .boxed-container.w-full.bg-light-500 -->
        <!--          .mx-6.d-flex.align-center.gap-2 -->
        <!--              span © 2022 44 -->
        <!--                  a.text-decoration-none(href="https://azucdent.net" target="_blank") -->
        <!--                      | AZUCDENT -->
        <!--              v-avatar.border-1.border-light-300(size="75") -->
        <!--                  // <img :src="hamza_url" class="border-1 border-light-300"> -->
        <!--              v-avatar(size="75") -->
        <!--                  // <img :src="fayed_url"> -->
        <!--              span.d-sm-inline.d-none -->
        <!--                  a.me-6.text--secondary.text-decoration-none(href="https://themeselection.com/products/category/download-free-admin-templates/" target="_blank") -->
        <!--                      | Freebies -->
        <!--                  a.me-6.text--secondary.text-decoration-none(href="https://themeselection.com/blog/" target="_blank") -->
        <!--                      | Blog -->
        <!--                  a.text--secondary.text-decoration-none(href="https://github.com/themeselection/materio-vuetify-vuejs-admin-template-free/blob/main/LICENSE" target="_blank") -->
        <!--                      | MIT Licence -->
        <v-snackbar v-model="notify.show" text top :color="notify.color">
            {{ notify.text }}<template #action="{ attrs }">
                <q-btn color="pink" text v-bind="attrs" @click="notify.show = false">
                    Close
                </q-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<style lang="scss" scoped>
.v-list-item--active {
    border-left: solid 8px blue;

    // background-color: rgb(241, 241, 241);
}

.v-list-item--active i {
    color: blue !important;

    // background-color: rgb(241, 241, 241);
}
</style>
