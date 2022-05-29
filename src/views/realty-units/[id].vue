
<route lang="yaml">
name: realty_unit_detail
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">
import * as $date from 'dayjs'
import type { Realty, RealtyUnit, Reservation } from '@/composables'
import axios from 'axios';
import { serialize } from 'object-to-formdata';
import { useCustomerStore } from '@/stores/customer';
import useValidation from '@/composables/validations';
import { usePeopleStore } from '@/stores/peoples';
const g = getCurrentInstance()?.proxy
const { t } = useI18n()
const route = useRoute()
const stc = useCustomerStore()
const stp = usePeopleStore()

const { validate } = useValidation()
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
const selected_range = ref([])
const reservation = ref<Reservation>({ id: 0 })
const imgs = ref([])
const test = ref(true)
const show_add_dialog = ref(false)
const show_add_reservation = ref(false)
onMounted(() => {

    axios.get('realty-unit/' + route.params.id).then(res => realty_unit.value = res.data)
})

const st = ref(false)
const selected_customer = ref('')
const duration = computed(() => reservation.value.start_date + ' ~' + reservation.value.end_date)
function editRealty() {

    show_add_dialog.value = true
}
function addReservation() {
    reservation.value = {
        id: 0,
        realty_unit_id: realty_unit.value.id,
    }

    show_add_reservation.value = true
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

function saveReservation() {

    reservation.value.customer_id = selected_customer.value.id
    reservation.value.end_date = $date(reservation.value.start_date).add(reservation.value.months_count, 'months').format('YYYY/MM/DD')
    axios.post('realty-unit-reservation', reservation.value).then(res => {

    })



}


function optionsFn(date) {
    if (!reservation.value.start_date) return
    return date >= reservation.value.start_date
}

</script>

<template lang="pug">
d-page
    template(#tools)
        q-btn(color="primary" icon="mdi-pencil" @click="editRealty" :label="t('button.edit')")
        q-btn(color="primary" icon-color="info" icon="add" @click="addReservation" :label="t('button.add') + ' ' + t('pages.reservations', 2)")
    h5(class="my-4")
        q-icon(class="mx-2" name='circle' color="info")
        |{{ t('general.details') }}
    vr-table(:data="realty_unit" :headers="['id', 'code', 'type', 'realty|realty.name', 'state', 'floor', 'size', 'price', 'description', 'docs']")
        template(#docs)
            .flex.center.justify-center.gap-2
                el-image(style="height:100px" v-for="(doc, i) in realty_unit.docs" :key="i" :src="$storage + doc.path" alt="Image" preview height="120" width="180")
    h5(class="my-4")
        q-icon(class="mx-2" name='circle' color="info")
        |{{ t('pages.reservations') }}
    el-table(class="mt-4" :data="realty_unit.reservations" style="width: 100%" stripe)
        el-table-column(prop="start_date" :label="t('inputs.start_date')" width="180")
        el-table-column(prop="end_date" :label="t('inputs.end_date')")
        el-table-column(prop="months_count" :label="t('inputs.months_count')")
        el-table-column(prop="customer" :label="t('inputs.customer')")
            template(#default="scope")
                |{{ scope.row.customer.name }}
        el-table-column(prop="rent_value_per_month" :label="t('inputs.rent_value_per_month')")
        el-table-column(prop="state" :label="t('inputs.state')")
            template(#default="scope")
                q-chip(square :label='scope.row.state' :class="scope.row.state")
        el-table-column(prop="options" :label="t('inputs.options')")
            template(#default="scope")
                q-btn(size="sm" round icon="mdi-eye" color="info")



    d-dialog(v-model="show_add_dialog" :title="t('button.edit') + ' ' + t('pages.realty_units', 2)" @save="updateRealty()")
        q-select(outline v-model="realty_unit.type" :options="['apartment', 'market']" :label="t('inputs.type')" :rules="[v => validate('required', v)]")
        q-input(v-model="realty_unit.floor" type="number" :label="t('inputs.floor')" :rules="[v => validate('required|max:3', v)]")
        q-input(v-model="realty_unit.size" type="number" :label="t('inputs.size')" :rules="[v => validate('required', v)]")
        q-input(size="sm" v-model="realty_unit.code" type="text" :label="t('inputs.code')" :rules="[v => validate('required|max:6', v)]")
        q-file(v-model="imgs" multiple :label="t('inputs.docs')")
        q-input(v-model="realty_unit.description" type="textarea" :label="t('inputs.description')" :rules="[v => validate('required', v)]")


    d-dialog(v-model="show_add_reservation" :title="t('titles.add_reservation')" @save="saveReservation()" :width="700")
        q-select(v-model="selected_customer" use-input option-label="name" option-value="id" :options="stp.customers" :label="t('inputs.customer')" :rules="[v => validate('required', v)]")
            template(#no-option)
                q-btn(:label="t('button.load_data')" block color="info" class="m-2" @click="stp.fetch('customer')")
        q-input(v-model="reservation.start_date" @click="st = !st" mask="date"  :label="t('inputs.start_date')" :rules="[v => validate('required ', v)]")
            template(#append)
                q-icon(name="event" class="cursor-pointer")
                    q-popup-proxy(v-model="st" :offset="[400, 0]")
                        q-date(v-model="reservation.start_date" minimal @update:modelValue="st = false")
        q-input(v-model="reservation.months_count" type="number" :label="t('inputs.months_count')" :rules="[v => validate('required ', v)]")
        q-input(v-model="reservation.rent_value_per_month" type="number" :label="t('inputs.rent_value_per_month')" :rules="[v => validate('required ', v)]")
        q-input(v-model="reservation.due_date" type="number" :label="t('inputs.due_date')" :rules="[v => validate('required ', v)]")

  
</template>
<style>
</style>
