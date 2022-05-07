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
const responsive = ref(false)
const collapsed = ref<boolean>(true)

const selectedKeys = ref([])

watch(selectedKeys, (new_val) => {
    console.log(new_val)
    if (responsive.value) setTimeout(() => {
        collapsed.value = true
    }, 250);
})

const ml = computed(() =>
    collapsed.value ? '0px' : '200px'
)
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


const onCollapse = (collapsed: boolean, type: string) => {
    //console.log(collapsed, type);
};

const onBreakpoint = (broken: boolean) => {
    console.log(broken);
    responsive.value = broken
};

function change() {
    console.log('yes')
}
</script>

<template>


    <a-layout>
        <a-layout-header :style="{ position: 'fixed', zIndex: 1, width: '100%' }">
            <a-button @click="() => (collapsed = !collapsed)">close</a-button>

        </a-layout-header>
        <a-layout :style="{ marginLeft: ml }">
            <a-layout-sider breakpoint="lg" :collapsedWidth="0" v-model:collapsed="collapsed" @breakPoint="onBreakpoint"
                :style="{ overflow: 'auto', height: '100vh', position: 'fixed', left: 0, top: 0, bottom: 0 }">


                <div class="logo" />
                <a-menu v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
                    <a-menu-item key="1">
                        <user-outlined />
                        <span class="nav-text">nav 1</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <video-camera-outlined />
                        <span class="nav-text">nav 2</span>
                    </a-menu-item>
                    <a-menu-item key="3">
                        <upload-outlined />
                        <span class="nav-text">nav 3</span>
                    </a-menu-item>
                    <a-menu-item key="4">
                        <bar-chart-outlined />
                        <span class="nav-text">nav 4</span>
                    </a-menu-item>
                    <a-menu-item key="5">
                        <cloud-outlined />
                        <span class="nav-text">nav 5</span>
                    </a-menu-item>
                    <a-menu-item key="6">
                        <appstore-outlined />
                        <span class="nav-text">nav 6</span>
                    </a-menu-item>
                    <a-menu-item key="7">
                        <team-outlined />
                        <span class="nav-text">nav 7</span>
                    </a-menu-item>
                    <a-menu-item key="8">
                        <shop-outlined />
                        <span class="nav-text">nav 8</span>
                    </a-menu-item>
                </a-menu>
            </a-layout-sider>


            <a-layout-content :style="{ margin: '24px 16px 0', overflow: 'initial' }">
                <div :style="{ padding: '24px', background: '#fff', textAlign: 'center' }">

                    ...
                    <br />
                    Really
                    {{ ml }}
                    {{ collapsed }}
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    long
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    ...
                    <br />
                    content
                </div>
            </a-layout-content>

        </a-layout>
        <a-layout-footer :style="{ textAlign: 'center' }">
            Ant Design ©2018 Created by Ant UED
        </a-layout-footer>
    </a-layout>
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
