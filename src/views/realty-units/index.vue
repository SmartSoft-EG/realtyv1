
<route lang="yaml">
name: realty_units
meta: 
    auth: true
    group: realty_management
</route>

<script setup lang="ts">
import { useRealtyUnitStore } from '@/stores/realtyUnit'
import type { RealtyUnit } from '~/composables'
import { pick } from '~/composables'
import { serialize } from 'object-to-formdata';
import { useRealtyStore } from '@/stores/realty';
import { usePeopleStore } from '@/stores/peoples';
import useValidation from '@/composables/validations';

const { t } = useI18n()
const cols = ['id', 'code', 'floor', 'size', 'state', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: t('inputs.' + c),
        align: 'left',
    }
}))
const str = useRealtyStore()
const stu = useRealtyUnitStore()
const stp = usePeopleStore()
const total_percent = computed(() => realty_unit.value.owners.reduce((a, b) => a + parseFloat(b.percent), 0))

const new_owner = ref({

})
const show_add_owner = ref(false)

onMounted(() => {
    if (stu.list.length == 0) stu.fetch()
    str.fetchRealty()
})

const { validate } = useValidation()

const realty_unit = ref<RealtyUnit>({
    id: 0,
    size: '',
    code: '',
    description: '',
})

const search = ref('')
const imgs = ref([])

function addRealtyUnit() {
    realty_unit.value = {
        id: 0,
        type: '',
        code: '',
        description: '',
        owners: []
    }
    stu.show_add_dialog = true
}


// function editRealty(rt: Realty) {
//     realty.value = rt
//     stu.show_add_dialog = true
// }

function addOwner() {
    new_owner.value = { id: 0, percent: 100 - total_percent.value, name: '' }
    show_add_owner.value = true
}

function saveOwner() {
    if (realty_unit.value.owners.some(r => r.id == new_owner.value.id)) return alert('تم اضافة هذا المالك من قبل')
    if (new_owner.value.percent < 1) return alert('percent value must be positive')
    if (total_percent.value + new_owner.value.percent > 100) return alert('النسبة يجب الا تتخطي 100')

    new_owner.value.name = stp.owners.find(o => o.id == new_owner.value.id)?.name
    realty_unit.value.owners.push(new_owner.value)
    show_add_owner.value = false
    new_owner.value = { id: 0, percent: 100, name: '' }
}

function saveRealtyUnit() {
    realty_unit.value.imgs = imgs.value

    const data = serialize(realty_unit.value, {
        indices: true,
    })
    stu.save(data)
}


function removeOwner(owner_id: number) {
    realty_unit.value.owners = realty_unit.value.owners.filter(o => o.id !== owner_id)
}

function deleteRealtyUnit(id: number) {
    stu.delete(id)
}

</script>

<template lang="pug">
d-page(@refresh="str.fetchRealty()")
    template(#tools)
        q-btn(outlined color="primary" @click="addRealtyUnit" icon="add" :label="t('general.add') + ' ' + t('general.realty_units', 2)")
    q-table( :filter="search" :pagination="{ rowsPerPage: 10 }" flat :rows="stu.list" :columns="columns" row-key="id")
        template(#top-left)
            q-input(v-model="search" outlined dense type="text")
                template(#prepend)
                    q-icon(name="search")
        template(#body-cell-options="props")
            td
                q-btn(icon="mdi-eye" class="mx-1" round size="sm" color="info" :to="$route.path + '/' + props.row.id") 
                q-btn(size="sm"  class="mx-1" round color="primary" icon="mdi-pencil" @click="editRealty(props.row)") 
                q-btn(size="sm" class="mx-1"  round color="negative" icon="mdi-close" @click="deleteRealtyUnit(props.row.id)")
    d-dialog(v-model="stu.show_add_dialog" :title="t('button.add') + ' ' + t('pages.realty_units', 2)" @save="saveRealtyUnit()" :width="800")
        .grid.grid-cols-12.gap-3
            div(class="col-span-12 sm:col-span-6")
                q-select(v-model="realty_unit.realty_id" :label="t('pages.realty', 2)" :options="str.realty_list" option-label="name" option-value="id" emit-value map-options :rules="[v => validate('required', v)]")
                q-input(v-model="realty_unit.floor" type="number" :label="t('inputs.floor')" :rules="[v => validate('required|max:3', v)]")
                q-input(v-model="realty_unit.size" type="number" :label="t('inputs.size')" :rules="[v => validate('required', v)]")
            div(class="col-span-12 sm:col-span-6")
                q-select(v-model="realty_unit.type" :options="['apartment', 'market']" :label="t('inputs.type')" :rules="[v => validate('required', v)]")
                q-input(size="sm" v-model="realty_unit.code" type="text" :label="t('inputs.code')" :rules="[v => validate('required|max:6', v)]")
                q-file(v-model="imgs" multiple :label="t('inputs.docs')")
        q-input(v-model="realty_unit.description" type="textarea" :label="t('inputs.description')" :rules="[v => validate('required', v)]")
        q-btn(flat color="info" icon="add" :label="t('button.add') + ' ' + t('general.owner')" @click="addOwner")
        table.tbl
            thead
                tr
                    th {{ t('inputs.owners', 1) }}
                    th {{ t('inputs.percent') }}
                    th {{ t('inputs.options') }}
            tbody
                tr(v-for="o in realty_unit.owners" :key="o.id")
                    td {{ o.name }}
                    td
                        input.b-1.p-1.w-20.text-center(type="number" v-model="o.percent")
                    td
                        q-btn(round color="negative" size="sm" @click="removeOwner(o.id)" icon="close")
                tr.footer
                    td total
                    td {{ total_percent }} %
                        br
                        span.text-red(v-show="total_percent != 100")
                            .
                                this should be
                                100 before save
                    td
                        span.bg-green-100.p-1(v-show="total_percent == 100")
                            | ok
    d-dialog(v-model="show_add_owner" title="Add owner" @save="saveOwner")
        q-select(label="select owner" :options="stp.owners" v-model="new_owner.id" option-value="id" option-label="name" emit-value map-options)
            template(#no-option)
                q-btn(label="load owners" @click="stp.fetch('owner')")
        q-input(label="percent" type="number" v-model="new_owner.percent")
</template>

<style scoped>
.tbl {
    margin: 10px;
    width: 90%;
    border-collapse: collapse;
}

.tbl td,
th {
    border: solid 1px rgb(212, 212, 212);
    padding: 10px;
    text-align: center;
    align-items: center;
}

.tbl th {
    background-color: azure;
}

.footer {
    background-color: rgb(255, 255, 255);
}
</style>
