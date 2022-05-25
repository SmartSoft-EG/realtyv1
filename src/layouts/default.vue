<script setup lang="ts">
import { useAuth } from '@websanova/vue-auth'
import nestedGroupby from 'nested-groupby'
import { useQuasar } from 'quasar';
import { useI18n } from 'vue-i18n'

import axios from 'axios'
import routes from '~pages'
import { useNotifyStore } from '@/stores/notification'
import { json } from 'stream/consumers';
const q = useQuasar()
const $auth = useAuth()
const notify = useNotifyStore()
const isDrawerOpen = ref<Boolean>(null)
const visible_routes = computed(() => routes.filter(r => (r.meta && !r.meta.hide) || !r.meta))
const public_routes = computed(() => visible_routes.value.filter(r => !r.meta || (r.meta && !r.meta.auth)))
const auth_routes = computed(() => visible_routes.value.filter(r => (r.meta && r.meta.auth == true) || (r.meta && r.meta.auth && $auth.check(r.meta.auth.roles))))
const grouped_auth_routes = computed(() => nestedGroupby(auth_routes.value, ['meta.group']))
const rtl = ref(false)
const { t, locale } = useI18n()
const bar = ref(null)

onMounted(() => {
    setLocal()

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

        //set direction
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

watch(() => notify.loading, (new_val) => {
    if (new_val) bar.value.start()
    else bar.value.stop()
})

function logout() {
    axios.post('auth/logout').then(() => {
        $auth.logout()
    })
}
function setLocal() {
    const dir = JSON.parse(localStorage.getItem('is_rtl') || true)
    rtl.value = dir
    q.lang.rtl = rtl.value
    //change lang
    if (rtl.value) locale.value = 'ar'
    else locale.value = 'en'
}

function changeLocal() {
    rtl.value = !rtl.value
    q.lang.rtl = rtl.value
    //change lang
    if (rtl.value) locale.value = 'ar'
    else locale.value = 'en'
    localStorage.setItem('is_rtl', JSON.stringify(rtl.value))
}
</script>

<template>
    <q-layout view="lhr LPR lFr'" :dir="rtl ? 'rtl' : 'ltr'" class="bg-blue-gray-100">

        <q-header flat>
            <q-toolbar class="h-16 bg-blue-gray-100">
                <q-btn dense flat color="primary" round icon="menu" @click="isDrawerOpen = !isDrawerOpen" />
                <q-space />
                <q-chip size="md" square clickable @click="$router.push('/')">
                    {{ $route.path == '/' ? t('general.title') : t('general.' + $route.name) }}
                </q-chip>

                <q-space />
                <q-btn dense round color="info" :label="rtl ? 'E' : 'ع'" @click="changeLocal()" />

            </q-toolbar>

            <q-ajax-bar ref="bar" in position="top" size="4px" color="red" />
        </q-header>

        <q-drawer show-if-above v-model="isDrawerOpen" :dir="rtl ? 'rtl' : 'ltr'" class="bg-blue-gray-200">
            <!-- drawer content -->

            <div v-if="$auth.check()" class="px-1  flex items-center   bg-blue-gray-400 text-blue-gray-600 h-16">
                <q-avatar size="20">
                    <img :src="$storage + $auth.user().img" alt>
                </q-avatar>

                <h5 class="text-center ma-2 white--text">
                    {{ $auth.user().name.split(/(\s).+\s/).join("") }}
                </h5>
                <div class="flex-1" />
                <q-btn icon="mdi-power" round size="sm" @click="logout()" />
            </div>

            <q-list>
                <template v-if="$auth.check()">
                    <template v-for="(group, name) in grouped_auth_routes" :key="name">
                        <q-item-label header> {{ t('general.' + name) }}</q-item-label>
                        <q-item clickable v-for="(route, i) in group" :key="route.path" active-class="active-class"
                            :to="route.path">
                            <q-item-section avatar top>
                                <q-avatar icon="folder" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label> {{ t('general.' + route.name) }}</q-item-label>
                            </q-item-section>

                        </q-item>
                    </template>
                </template>
            </q-list>



            <!-- <v-list-subheader>Genral</v-list-subheader>
          
            <template v-for="r2 in public_routes" :key="r2.path">
                <v-list-item :to="r2.path">
                    <v-list-item-title>{{ r2.name }}</v-list-item-title>
                </v-list-item>
            </template> -->



        </q-drawer>


        <q-page-container :dir="rtl ? 'rtl' : 'ltr'" class="bg-blue-gray-100">
            <router-view> </router-view>
        </q-page-container>

        <q-footer reveal flat class="bg-bluegray-200">
            <q-card flat class="bg-bluegray-200 text-blue-900">
                <q-card-section>
                    <div class="text-h6">Realty Management App 2022</div>
                </q-card-section>

            </q-card>
        </q-footer>
        <!--  v-footer.px-0.py-3.bg-light-400(height="400") -->
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


        <!-- <v-snackbar v-model="notify.show" text top :color="notify.color">
            {{ notify.text }}<template #action="{ attrs }">
                <q-btn color="pink" text v-bind="attrs" @click="notify.show = false">
                    Close
                </q-btn>
            </template>
        </v-snackbar> -->
    </q-layout>
</template>

<style lang="scss">
[dir="rtl"] i {
    margin-right: 0px !important;
}

[dir="rtl"] .q-item__section--side {
    padding-right: 0px !important;
}

[dir="rtl"] .q-drawer--left {
    right: 0;
    left: auto;
}

[dir="ltr"] .q-drawer--left {
    left: auto;
}

.active-class {
    color: rgb(42, 43, 53);
    background-color: #f1f5f9;
    font-weight: 900;
}

.active-class .q-item__label {

    font-weight: bold;
    color: rgb(29, 103, 232);
}





.active-class i {
    color: rgb(29, 103, 232);
}
</style>
