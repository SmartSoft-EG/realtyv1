
<route lang="yaml">
name: patients
meta: 
    auth: true
    group: realty_management
</route>

<script setup lang="ts">
import axios from 'axios'
import useValidation from '~/composables/validations'

import { useRealtyStore } from '~/stores/realty'
import type { Realty } from '~/composables'

import { serialize } from 'object-to-formdata';
const { t } = useI18n()
const { validate } = useValidation()
const cols = ['id', 'name', 'code', 'address', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: c,
        align: 'left',
    }
}))
const data = ref([])
const str = useRealtyStore()

onMounted(() => {
    fetchData()
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
function fetchData() {
    fetch('https://azucdent.net/api/patients?all=true')
        .then(response => response.json())
        .then(d => data.value = d)
        .catch(err => console.error(err));
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
    <d-page @refresh="fetchData()"><template #tools>
            <q-btn color="primary" @click="addRealty" icon="mdi-plus"
                :label="t('button.add') + ' ' + t('general.realty', 2)"></q-btn>
        </template>
        <q-table title="Realty" :filter="search" flat :rows="data" :columns="columns" row-key="id"><template #top-right>
                <q-input v-model="search" outlined dense type="text"><template #append>
                        <q-icon name="search"></q-icon>
                    </template></q-input>
            </template><template #body-cell-options="props">
                <td class="flex gap-2 items-center">
                    <q-btn icon="mdi-eye" size="sm" round color="positive" :to="'/realty/' + props.row.id"></q-btn>
                    <q-btn icon="mdi-pencil" size="sm" round color="info" @click="editRealty(props.row)"></q-btn>
                    <q-btn icon="mdi-close" size="sm" round color="red" @click="deleteRealty(props.row.id)"></q-btn>
                </td>
            </template></q-table>


    </d-page>
</template>
