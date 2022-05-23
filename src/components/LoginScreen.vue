<script setup lang="ts">
import { useAuth } from '@websanova/vue-auth'
import { useQuasar } from 'quasar'

import axios from 'axios'
const email = ref('')
const password = ref('')
const has_error = ref(false)
const loading = ref(false)
const form = ref(null)
const auth = useAuth()
const q = useQuasar()

onMounted(() => {

})

function login() {
    // get the redirect object
    if (!email.value || !password.value) return q.notify('Please fill all required data')

    loading.value = true

    axios.get('csrf-cookie').then((res) => {
        auth.login({
            data: {
                email: email.value,
                password: password.value,
                useCredentails: true,
            },
            rememberMe: true,
            staySignedIn: true,
            fetchUser: true,
            redirect: {
                path: '/user',
            },
        })
            .then(
                (res) => {
                    console.log
                    loading.value = false
                },
                () => {
                    has_error.value = true
                    loading.value = false
                },
            )
    })
}


</script>

<template>
    <div class="flex center justify-center ">


        <q-card v-if="!auth.check()" square class="q-ml-auto q-mr-auto q-mt-lg" style="max-width: 400px">
            <q-card-section class="bg-primary text-center text-white text-h6">
                login
            </q-card-section>

            <q-card-section>
                <q-input id="email" v-model.trim="email" type="text" label="email" autofocus />
                <q-input id="password" v-model="password" type="password" label="Password" required /><br>
            </q-card-section>

            <q-card-actions>
                <q-btn class="fit" color="primary" @click.prevent="login">
                    Login
                </q-btn>
            </q-card-actions>
        </q-card>
        <q-card v-else class="my-card">
            <img src="https://cdn.quasar.dev/img/mountains.jpg">
            <q-card-section>
                {{ $auth.user() }}
            </q-card-section>
        </q-card>
    </div>
</template>

<style lang="scss" scoped>
</style>
