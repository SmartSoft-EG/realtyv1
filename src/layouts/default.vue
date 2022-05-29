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
    <q-layout view="lHr LPR lFr" class="bg-blue-gray-100">

        <q-header flat>
            <q-toolbar class="h-16 bg-blue-gray-100">
                <q-btn dense flat color="primary" round icon="menu" @click="isDrawerOpen = !isDrawerOpen" />
                <q-space />
                <q-chip color="primary" class="text-white" square clickable @click="$router.push('/')">
                    {{ $route.path == '/' ? t('general.title') : t('pages.' + $route.name) }}
                </q-chip>

                <q-space />
                <q-btn v-show="q.lang.isoName == 'ar'" dense round color="primary" outline label="e"
                    @click="setLang('en')" />
                <q-btn v-show="q.lang.isoName == 'en-US'" dense round color="primary" outline label="ع"
                    @click="setLang('ar')" />

            </q-toolbar>

            <q-ajax-bar ref="bar" in position="top" size="4px" color="red" />
        </q-header>

        <q-drawer show-if-above v-model="isDrawerOpen" class="bg-blue-gray-200">
            <!-- drawer content -->
            <div v-if="$auth.check()" class="px-1  flex items-center   bg-blue-gray-100 text-gray-700 h-15">
                <q-avatar size="20">
                    <img :src="$storage + $auth.user().img" alt>
                </q-avatar>
                <h5 class="text-center ma-2 white--text">
                    {{ $auth.user().name.split(/(\s).+\s/).join("") }}
                </h5>
                <div class="flex-1" />
                <q-btn icon="mdi-power" round color="negative" outline size="sm" @click="logout()" />
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
                                <q-item-label> {{ t('pages.' + route.name) }}</q-item-label>
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


        <q-page-container class="bg-blue-gray-100">
            <router-view> </router-view>
        </q-page-container>

        <q-footer reveal flat class="bg-bluegray-200">
            <q-card flat class="bg-bluegray-200 text-blue-900">
                <q-card-section>
                    <div class="text-h6">Realty Management App 2022</div>
                </q-card-section>

            </q-card>
        </q-footer>

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
h5 {
    font-size: 20px;
}

.active-class {
    color: rgb(42, 43, 53);
    background-color: #f1f5f9;
    font-weight: 900;
}

.active-class .q-item__label {

    font-weight: bold;
    color: rgb(232, 97, 1);
}





.active-class i {
    color: rgb(232, 97, 1);
    ;
}

.active {

    background: green;
    color: white;
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
