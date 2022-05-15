
<route lang="yaml">
meta: 
    auth: true
    group: العقارات
</route>

<script setup lang="ts">
import { useRealtyStore } from '~/stores/realty'
import type { Realty } from '~/composables/interfaces'
import { serialize } from 'object-to-formdata';

const cols = ['id', 'name', 'code', 'address', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: c,
        align: 'left',
    }
}))
const str = useRealtyStore()

onMounted(() => {
    str.fetchRealty()
})
const realty = ref<Realty>({
    id: 0,
    name: '',
    address: '',
    code: '',
    description: '',
})
const search = ref('')
const imgs = ref([])

function addRealty() {
    realty.value = {
        id: 0,
        name: '',
        address: '',
        code: '',
        description: '',
    }
    str.show_add_dialog = true
}


function editRealty(rt: Realty) {
    realty.value = rt
    str.show_add_dialog = true
}

function saveRealty() {
    const data = serialize(
        {
            id: realty.value.id,
            name: realty.value.name,
            description: realty.value.description,
            code: realty.value.code,
            address: realty.value.address,
            imgs: imgs.value
        }
    )
    str.saveRealty(data)
}

function deleteRealty(id: number) {
    str.deleteRealty(id)
}

</script>

<template>
<d-page @refresh="str.fetchRealty()"><template #tools><v-btn color="primary" @click="addRealty">Add realty</v-btn></template>

<q-table title="Realty" :filter="search" flat :rows="str.realty_list" :columns="columns" row-key="id"><template #top-right><q-input v-model="search" outlined dense type="text"><template #append><q-icon name="search"></q-icon></template></q-input></template><template #body-cell-options="props"><td class="flex gap-2"><v-btn icon="mdi-eye" variant="outlined" density="compact" color="success" :to="'/realty/' + props.row.id"></v-btn><v-btn icon="mdi-pencil" variant="outlined" density="compact" color="primary" @click="editRealty(props.row)"></v-btn><v-btn icon="mdi-close" variant="outlined" density="compact" color="error" @click="deleteRealty(props.row.id)"></v-btn></td></template></q-table>


<d-dialog v-model="str.show_add_dialog" title="Add realty" @save="saveRealty()"><q-input v-model="realty.name" type="text" label="name"></q-input><q-input v-model="realty.code" type="text" label="code"></q-input><q-input v-model="realty.address" type="text" label="address"></q-input><q-input v-model="realty.description" type="textarea" label="description"></q-input><q-file v-model="imgs" multiple label="images"></q-file></d-dialog></d-page>
</template>
