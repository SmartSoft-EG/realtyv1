
<route lang="yaml">
name: user_details
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">

import axios from 'axios';
//import { serialize } from 'object-to-formdata';
//const g = getCurrentInstance()?.proxy
const { t } = useI18n()
const route = useRoute()

const user = ref({
    id: 0,
    name: '',
    address: 0,
})


//const show_add_dialog = ref(false)

onMounted(() => {
    fetchUser()
})

function fetchUser() {
    axios.get('user/' + route.params.id).then(res => user.value = res.data)
}

// function editRealty() {

//     show_add_dialog.value = true
// }

function editUSer() {


    // axios.post('user/' + user.value.id, user.value).then(res => {
    //     //user.value = res.data
    //     //show_add_dialog.value = false
    // })
}



</script>

<template lang="pug">
d-page(@refresh="fetchUser()")
    template(#tools)
        q-btn(color="primary" icon="edit" flat @click="editUSer()" :label="t('button.edit') + ' ' + t('pages.users', 2)")
    vr-table(:data="user" :headers="['id', 'name', 'address', 'telephone', 'email', 'job', 'roles']")

//-   d-dialog(v-model="show_add_dialog" title="Add user" @save="updateRealty()")
//-     q-input(v-model="user.name" type="text" label="name")
//-     q-select(v-model="user.type" :options="['t1', 't2', 't3']" label="type")
//-     q-input(v-model="user.code" type="text" label="code")
//-     q-input(v-model="user.floor" type="text" label="floor")
//-     q-input(v-model="user.description" type="textarea" label="description")
//-     q-file(v-model="imgs" multiple label="files")
</template>
