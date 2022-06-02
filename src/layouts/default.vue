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
    let langs = { 'ar': langAr, 'en': langEn }
    locale.value = lang
    q.lang.set(langs[lang])
    localStorage.setItem('current_lang', lang)
}

</script>

<template lang="pug">
q-layout.bg-blue-gray-100(view="lHr LPR lfr")

    q-header(flat)
        q-toolbar.h-16.bg-blue-gray-100
            q-btn(dense flat color="primary" round icon="menu" @click="isDrawerOpen = !isDrawerOpen")
            q-space
            span.clickable.text-blue-gray-900.font-bold(@click="$router.push('/')")
                |{{ $route.path == '/' ? t('general.title') : t('pages.' + $route.name) }}
            q-space
            q-btn(v-show="q.lang.isoName == 'ar'" dense round color="primary" outline label="e" @click="setLang('en')")
            q-btn(v-show="q.lang.isoName == 'en-US'" dense round color="primary" outline label="ع" @click="setLang('ar')")
        q-ajax-bar(ref="bar" in position="top" size="3px" color="primary")

    // drawer content
    q-drawer(show-if-above v-model="isDrawerOpen")
        q-list.bg-blue-gray-600.h-full(dark)
            .h-30.bg-blue-gray-100
                .px-1.flex.items-center.text-gray-700.h-15(v-if="$auth.check()")
                    q-avatar(size="20")
                        img(:src="$storage + $auth.user().img" alt)
                    h5.text-center.ma-2.white--text {{ $auth.user().name.split(/(\s).+\s/).join("") }}
                    .flex-1
                    q-btn(icon="mdi-power" round color="negative" outline size="sm" @click="logout()")
                q-item.bg-blue-gray-600.h-15.rounded-tr-30(dense active-class="active-class text-black bg-light-900" to="/user")
                    q-item-section
                        q-item-label {{ t('pages.users', 2) }}
            template(v-if="$auth.check()")
                template(v-for="(group, name) in grouped_auth_routes" :key="name")
                    q-item-label.text-white(header)
                        | {{ t('general.' + name) }}
                    q-item(dense clickable v-for="(route, i) in group" :to="route.path" exact-active-class="exact-active-class" active-class="active-class" :exact="false")
                        q-item-section(avatar top)
                            q-avatar(icon="circle")
                        q-item-section
                            q-item-label {{ t('pages.' + route.name) }}
                    q-separator(spaced inset vertical)
                    hr

    q-page-container(class="bg-blue-gray-100 sm:p10")
        router-view
        q-page-scroller(position="bottom-right" :scroll-offset="150" :offset="[10, 10]")
            q-btn(icon="keyboard_arrow_up" outline round color="info")

    q-footer.bg-blue-gray-600(flat)
        .h-3.bg-blue-gray-100.rounded-bl-30(style="height: 50px;")
        q-card.bg-blue-gray-600.text-light-300.relative.bg-blue-gray-600(flat)
            q-card-section.text-center
                .text-h6 Realty Management App 2022
                .text-h7 Realty Management App 2022

</template>

<style lang="scss">
h5 {
    font-size: 20px;
}

.active-class {
    color: rgb(42, 43, 53);
    background-color: #f1f5f9;
    font-weight: 900;
    // border-left: solid 8px rgb(6, 31, 216);

    border-radius: 30px 0px 0px 30px;
    margin-left: 10px;

}






.active-class i {
    color: rgb(6, 31, 216);
    font-weight: 900;
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
