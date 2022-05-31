<script setup lang="ts">
import { useAuth } from '@websanova/vue-auth'
import nestedGroupby from 'nested-groupby'
import { useQuasar } from 'quasar';
import { useI18n } from 'vue-i18n'

import axios from 'axios'
import routes from '~pages'
import { useNotifyStore } from '@/stores/notification'
import langAr from 'quasar/lang/ar'
import langEn from 'quasar/lang/en-US'

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
    setLang(localStorage.getItem('current_lang') || 'ar')


    //applyLocal()
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


// function changeLocal() {
//     let cl = q.lang.name
//     q.lang.rtl = !q.lang.rtl
//     //change lang
//     applyLocal()
//     localStorage.setItem('is_rtl', JSON.stringify(q.lang.rtl))
// }

function setLang(lang: string) {
    let langs = {
        'ar': langAr,
        'en': langEn
    }
    locale.value = lang
    q.lang.set(langs[lang])
    localStorage.setItem('current_lang', lang)


}
function setLangE() {
    localStorage.setItem('current_lang', 'en-US')
    q.lang.set(langEn)
    locale.value = 'en-US'
}

function setLangA() {
    localStorage.setItem('current_lang', 'ar')
    q.lang.set(langAr)
    locale.value = 'ar'
}
</script>

<template>
    <q-layout class="bg-blue-gray-100" view="lHr LPR lFr">
        <q-header flat>
            <q-toolbar class="h-16 bg-blue-gray-100">
                <q-btn dense flat color="primary" round icon="menu" @click="isDrawerOpen = !isDrawerOpen"></q-btn>
                <q-space></q-space>
                <span class="clickable text-blue-gray-900 font-bold " @click="$router.push('/')">{{ $route.path ==
                        '/' ? t('general.title') : t('pages.' + $route.name)
                }}</span>
                <q-space></q-space>
                <q-btn v-show="q.lang.isoName == 'ar'" dense round color="primary" outline label="e"
                    @click="setLang('en')"></q-btn>
                <q-btn v-show="q.lang.isoName == 'en-US'" dense round color="primary" outline label="ع"
                    @click="setLang('ar')"></q-btn>
            </q-toolbar>
            <q-ajax-bar ref="bar" in position="top" size="4px" color="red"></q-ajax-bar>
        </q-header>

        <q-drawer class="bg-blue-gray-600" show-if-above v-model="isDrawerOpen">
            <!-- drawer content -->
            <div class="px-1 flex items-center bg-blue-gray-100 text-gray-700 h-15" v-if="$auth.check()">
                <q-avatar size="20"><img :src="$storage + $auth.user().img" alt></q-avatar>
                <h5 class="text-center ma-2 white--text">{{ $auth.user().name.split(/(\s).+\s/).join("") }}</h5>
                <div class="flex-1"></div>
                <q-btn icon="mdi-power" round color="negative" outline size="sm" @click="logout()"></q-btn>
            </div>

            <q-list dark>
                <template v-if="$auth.check()"><template v-for="(group, name) in grouped_auth_routes" :key="name">
                        <q-item-label header class="text-white">{{ t('general.' + name) }}</q-item-label>

                        <q-item dense clickable v-for="(route, i) in group" :to="route.path"
                            exact-active-class="exact-active-class" active-class="active-class" :exact="false">
                            <q-item-section avatar top>
                                <q-avatar icon="circle"></q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{ t('pages.' + route.name) }}</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-separator spaced inset vertical />
                        <hr>
                    </template>

                </template>
            </q-list>
        </q-drawer>

        <q-page-container class="bg-blue-gray-100 sm:p10">
            <router-view></router-view>
            <q-page-scroller position="bottom-right" :scroll-offset="150" :offset="[10, 10]">
                <q-btn icon="keyboard_arrow_up" outline round color="info"></q-btn>
            </q-page-scroller>
        </q-page-container>
        <q-footer class="bg-bluegray-600" reveal flat>
            <q-card class="bg-bluegray-600 text-light-300" flat>
                <q-card-section>
                    <div class="text-h6">Realty Management App 2022</div>
                </q-card-section>
            </q-card>
        </q-footer>
    </q-layout>
</template>

<style lang="scss">
h5 {
    font-size: 20px;
}

.active-class {
    color: rgb(42, 43, 53);
    background-color: #f1f5f9;
    font-weight: 900;
    border-left: solid 10px rgb(42, 43, 53);
}

.active-class .q-item__label {

    font-weight: 900;
    color: rgb(42, 43, 53);
}





.active-class i {
    color: rgb(42, 43, 53);
    font-weight: 900;
    ;
}

.active {

    background: green;
    color: white;
}

.exact-active-class {
    color: red
}

.waiting {

    background: rgb(246, 255, 167);

}

.performed {

    background: rgb(0, 110, 0);
    color: white;
}

.closed {

    background: rgb(83, 83, 255);
    color: white;
}
</style>
