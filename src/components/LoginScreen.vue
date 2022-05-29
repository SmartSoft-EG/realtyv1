<script setup lang="ts">
import { useAuth } from '@websanova/vue-auth'
import { useQuasar } from 'quasar'
import axios from 'axios'
import useValidation from '@/composables/validations';
const email = ref('')
const password = ref('')
const has_error = ref(false)
const loading = ref(false)
const auth = useAuth()
const q = useQuasar()
const { t } = useI18n()
const { validate } = useValidation()
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
                // useCredentails: true,
            },
            //rememberMe: true,
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


        <q-card flat v-if="!auth.check()" square class="q-ml-auto q-mr-auto q-mt-lg"
            style="min-width:400px;max-width: 700px">
            <q-card-section class="bg-primary text-center text-white text-h6">
                {{ t('pages.login') }}
            </q-card-section>

            <q-card-section>
                <q-form @submit="login" class="q-gutter-md">
                    <q-input id="email" v-model.trim="email" type="email" :label="t('inputs.email')"
                        :rules="[v => validate('email', v)]" autofocus />

                    <q-input id="password" v-model="password" type="password" :label="t('inputs.password')"
                        :rules="[v => validate('required|min:4', v)]" /><br>

                    <q-btn color="primary" :loading="loading" type="submit">
                        {{ t('button.login') }}
                    </q-btn>
                </q-form>
            </q-card-section>


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
