<script setup lang="ts">
import { useAuth } from '@websanova/vue-auth'
import axios from 'axios'
const email = ref('')
const password = ref('')
const has_error = ref(false)
const loading = ref(false)
const form = ref(null)
const auth = useAuth()
onMounted(() => {

})

function login() {
    // get the redirect object
    if (!form.value.validate())
        return
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


        <v-card variant="outlined" class="ma-2 mt-5" style="width:500px">
            <template v-if="!$auth.check()">
                <div class="font-bold text-center p-3 bg-sky-200">
                    login
                </div>
                <v-card-text class="">
                    <div v-if="has_error" class="bg-red-400 p-2 mb-6 rounded-md text-center text-white">
                        Login Data Incorrect
                    </div>
                    <v-form ref="form" lazy-validation @keyup.native.enter="login">
                        <v-text-field ref="loginTxt" v-model="email" prepend-icon="mdi-account" density="comfortable"
                            variant="outlined" focusable label="Email or telephone"
                            :rules="[v => !!v || 'E-mail or telephone is required']" required />
                        <v-text-field v-model="password" prepend-icon="mdi-lock" density="comfortable"
                            variant="outlined" type="password" label="Password"
                            :rules="[v => !!v || 'Password is required', v => (v && v.length >= 6) || 'Name must be at least 6 characters']"
                            required />
                    </v-form>

                    <div class="d-flex align-center justify-center">
                        <v-btn color="primary darken-1" large :loading="laoding" @click.stop="login">
                            Login
                        </v-btn>
                    </div>
                </v-card-text>
                <v-card-actions class="justify-center">
                    <div class="d-flex">
                        <v-btn text color="primary" @click.stop="$router.push('register')">
                            Register
                        </v-btn>
                        <v-btn text color="primary" @click.stop="resetEmail">
                            Reset Password
                        </v-btn>
                    </div>
                </v-card-actions>
            </template>
        </v-card>
    </div>
</template>

<style lang="scss" scoped>
</style>
