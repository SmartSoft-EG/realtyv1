
<route lang="yaml">
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">

import type { Realty } from '~/composables/interfaces'
import axios from 'axios';
import { serialize } from 'object-to-formdata';
const route = useRoute()

const realty = ref<Realty>({
    id: 0,
    name: '',
    code: '',
    unites: [],
})

const imgs = ref([])

const show_add_dialog = ref(false)
onMounted(() => {
    axios.get('realty/' + route.params.id).then(res => realty.value = res.data)
})



function editRealty() {

    show_add_dialog.value = true
}

function updateRealty() {
    const data = serialize({
        _method: 'put',
        id: realty.value.id,
        name: realty.value.name,
        description: realty.value.description,
        code: realty.value.code,
        imgs: imgs.value
    })

    axios.post('realty/' + realty.value.id, data).then(res => {
        realty.value = res.data
        show_add_dialog.value = false
    })
}



</script>

<template lang="pug">
d-page(@refresh="str.fetchRealty()")
    template(#tools)
        v-btn(color="primary" @click="editRealty")
            | Edit realty
    d-line(title="id" ) {{ realty.id }}
    d-line(title="name" ) {{ realty.name }}
    d-line(title="code" ) {{ realty.code }}
    d-line(title="description" ) {{ realty.description }}
    d-line(title="docs" )
        .flex.center.justify-center
            img.border-2.border-red.rounded-md.mx-2(v-for="(doc, i) in realty.docs" :key="i" :src="$storage + doc.path" style="max-height: 100px;")
    d-line(title="unites" ) {{ realty.unites }}
    d-dialog(v-model="show_add_dialog" title="Add realty" @save="updateRealty()")
        q-input(v-model="realty.name" type="text" label="name")
        q-input(v-model="realty.code" type="text" label="code")
        q-input(v-model="realty.address" type="text" label="address")
        q-input(v-model="realty.description" type="textarea" label="description")
        q-file(v-model="imgs" multiple label="files")
</template>
