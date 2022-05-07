
<route lang="yaml">
meta: 
    auth: true
    group: العقارات
</route>

<script setup lang="ts">
import { useRealtyStore } from '~/stores/realty'
import type { RealtyUnit } from '~/composables'
import { pick } from '~/composables'
import { serialize } from 'object-to-formdata';

const cols = ['id', 'name', 'code', 'state', 'address', 'options']
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
    str.fetchRealtyUnits()
    if (str.realty_list.length == 0) str.fetchRealty()
})

const realty_unit = ref<RealtyUnit>({
    id: 0,
    realty_id: 0,
    name: '',
    code: '',
    description: '',
})

const search = ref('')
const imgs = ref([])

function addRealtyUnit() {
    realty_unit.value = {
        id: 0,
        type: '',
        realty_id: 0,
        name: '',
        code: '',
        description: '',
    }
    str.show_add_dialog = true
}


// function editRealty(rt: Realty) {
//     realty.value = rt
//     str.show_add_dialog = true
// }

function saveRealtyUnit() {

    const data = serialize({ ...pick(['id', 'realty_id', 'type', 'name', 'code', 'description'], realty_unit.value), imgs: imgs.value })
    console.log(data)
    str.saveRealtyUnit(data)
}

// function deleteRealty(id: number) {
//     str.deleteRealty(id)
// }

</script>

<template lang="pug">
d-page(@refresh="str.fetchRealty()")
    template(#tools)
        v-btn(color="primary" @click="addRealtyUnit" v-text="'Add realty unite'")
    q-table(title="Realty units" :filter="search" flat :rows="str.realty_units" :columns="columns" row-key="id")
        template(#top-right)
            q-input(v-model="search" outlined dense type="text")
                template(#append)
                    q-icon(name="search")
        template(#body-cell-options="props")
            td.flex.gap-2
                v-btn(icon="mdi-eye" variant="outlined" density="compact" color="success"  :to="$route.path + '/' + props.row.id")
                v-btn(icon="mdi-pencil" variant="outlined" density="compact" color="primary"  @click="editRealty(props.row)")
                v-btn(icon="mdi-close" variant="outlined" density="compact" color="error"  @click="deleteRealty(props.row.id)")

    d-dialog(v-model="str.show_add_dialog" title="Add realty" @save="saveRealtyUnit()")
        q-select(v-model="realty_unit.realty_id" :options="str.realty_list" option-label="name" option-value="id" emit-value map-options )
        q-select(v-model="realty_unit.type" :options="['t1', 't2', 't3']" label="type")
        q-input(v-model="realty_unit.name" type="text" label="name")
        q-input(v-model="realty_unit.code" type="text" label="code")
        q-input(v-model="realty_unit.description" type="textarea" label="description")
        q-file(v-model="imgs"  multiple label="images")
</template>
