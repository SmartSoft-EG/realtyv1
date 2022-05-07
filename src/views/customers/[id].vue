
<route lang="yaml">
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">

import axios from 'axios';
//import { serialize } from 'object-to-formdata';
//const g = getCurrentInstance()?.proxy
const route = useRoute()

const customer = ref({
    id: 0,
    name: '',
    address: 0,
})


//const show_add_dialog = ref(false)

onMounted(() => {
    fetchCustomer()
})

function fetchCustomer() {
    axios.get('customer/' + route.params.id).then(res => customer.value = res.data)
}

// function editRealty() {

//     show_add_dialog.value = true
// }

function editCustomer() {


    // axios.post('customer/' + customer.value.id, customer.value).then(res => {
    //     //customer.value = res.data
    //     //show_add_dialog.value = false
    // })
}



</script>

<template lang="pug">
d-page(@refresh="fetchCustomer()")
    template(#tools)
        v-btn(color="primary" @click="editCustomer()" v-text="'Edit customer'")
    vr-table(:data="customer" :headers="['id', 'name', 'address', 'telephone', 'email', 'job']")

//-   d-dialog(v-model="show_add_dialog" title="Add customer" @save="updateRealty()")
//-     q-input(v-model="customer.name" type="text" label="name")
//-     q-select(v-model="customer.type" :options="['t1', 't2', 't3']" label="type")
//-     q-input(v-model="customer.code" type="text" label="code")
//-     q-input(v-model="customer.floor" type="text" label="floor")
//-     q-input(v-model="customer.description" type="textarea" label="description")
//-     q-file(v-model="imgs" multiple label="files")
</template>
