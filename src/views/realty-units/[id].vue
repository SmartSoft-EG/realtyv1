
<route lang="yaml">
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">

import type { Realty, RealtyUnit } from '@/composables/interfaces'
import axios from 'axios';
import { serialize } from 'object-to-formdata';
const g = getCurrentInstance()?.proxy
const route = useRoute()
// const images = computed(() => realty_unit.value.docs.map(d => {
//     return {
//         "itemImageSrc": g.$storage + d.path,
//         "thumbnailImageSrc": g.$storage + d.path,
//         "alt": "Description for Image 14",
//         "title": "Title 14"
//     }

// }))
const realty_unit = ref<RealtyUnit>({
    id: 0,
    name: '',
    reality_id: 0,
    type: 't1',
    description: '',
    floor: 0,
    code: '',
    docs: [],

})

const imgs = ref([])

const show_add_dialog = ref(false)

onMounted(() => {
    axios.get('realty-unit/' + route.params.id).then(res => realty_unit.value = res.data)
})



function editRealty() {

    show_add_dialog.value = true
}

function updateRealty() {
    const data = serialize({
        _method: 'put',
        id: realty_unit.value.id,
        name: realty_unit.value.name,
        description: realty_unit.value.description,
        code: realty_unit.value.code,
        imgs: imgs.value
    })

    axios.post('realty-unit/' + realty_unit.value.id, data).then(res => {
        realty_unit.value = res.data
        show_add_dialog.value = false
    })
}



</script>

<template>
    <d-page><template #tools>
            <v-btn color="primary" @click="editRealty" v-text="'Edit realty unit'"></v-btn>
        </template>
        <vr-table :data="realty_unit"
            :headers="['id', 'name', 'type', 'realty|realty.name', 'state', 'floor', 'price', 'code', 'description', 'docs']">
            <template #docs>
                <div class="flex center justify-center gap-2">
                    <Image v-for="(doc, i) in realty_unit.docs" :key="i" :src="$storage + doc.path" alt="Image" preview
                        height="120" width="180" />
                </div>
            </template>
        </vr-table>

        <!-- <Galleria :value="images" :numVisible="5" containerStyle="max-width: 640px">
            <template #item="slotProps">
                <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" style=" display: block;
                            max-width:600px" height="400" />
            </template>
            <template #thumbnail="slotProps">
                <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt"
                    style="width: 100%; display: block;" />
            </template>
        </Galleria> -->
        <d-dialog v-model="show_add_dialog" title="Add realty_unit" @save="updateRealty()">
            <q-input v-model="realty_unit.name" type="text" label="name"></q-input>
            <q-select v-model="realty_unit.type" :options="['t1', 't2', 't3']" label="type"></q-select>
            <q-input v-model="realty_unit.code" type="text" label="code"></q-input>
            <q-input v-model="realty_unit.floor" type="text" label="floor"></q-input>
            <q-input v-model="realty_unit.description" type="textarea" label="description"></q-input>
            <q-file v-model="imgs" multiple label="files"></q-file>
        </d-dialog>
    </d-page>
</template>
