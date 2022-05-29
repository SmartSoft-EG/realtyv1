
<route lang="yaml">
name: reservations
meta: 
    auth: true
    group: realty_management
</route>

<script setup lang="ts">
import * as $date from 'dayjs'

import { pick } from '@/composables'
import { serialize } from 'object-to-formdata';
import { useReservationStore } from '@/stores/reservations';
import { usePeopleStore } from '@/stores/peoples';
import { useRealtyUnitStore } from '@/stores/realtyUnit';
import useValidation from '@/composables/validations';

const cols = ['id', 'customer', 'unit_code', 'start_date', 'end_date', 'months_count', 'rent_value_per_month', 'state', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: t('inputs.' + c),
        align: 'left',
    }
}))
const { t } = useI18n()
const str = useReservationStore()
const stp = usePeopleStore()
const stu = useRealtyUnitStore()

const { validate } = useValidation()
const selected_customer = ref('')
const selected_unit = ref('')
const st = ref(false)
onMounted(() => {
    if (str.list.length == 0) str.fetch()
})

const reservation = ref({
    id: 0
})

const search = ref('')

function addReservation() {
    reservation.value = {
        id: 0,
    }
    str.show_add_dialog = true
}


function saveReservation() {
    reservation.value.realty_unit_id = selected_unit.value.id
    reservation.value.customer_id = selected_customer.value.id
    reservation.value.end_date = $date(reservation.value.start_date).add(reservation.value.months_count, 'months').format('YYYY/MM/DD')
    str.save(reservation.value)
}

// function deleteRealty(id: number) {
//     str.deleteRealty(id)
// }
const handleChange = (info: UploadChangeParam) => {
    let resFileList = [...info.fileList];

    // 1. Limit the number of uploaded files
    //    Only to show two recent uploaded files, and old ones will be replaced by the new
    resFileList = resFileList.slice(-2);

    // 2. read from response and show file link
    resFileList = resFileList.map(file => {
        if (file.response) {
            // Component will show file.url as link
            file.url = file.response.url;
        }
        return file;
    });
    //resFileList = resFileList.filter(f => f.status == 'error')

    realty_reservation.value.docs = resFileList;
};
</script>

<template lang="pug">
d-page(@refresh="str.fetch()")
    template(#tools)
        q-btn(icon="add" color="primary" @click="addReservation" :label="t('button.add') + ' ' + t('pages.reservations', 2)")
    q-table(title=" Realty units" :filter="search" flat :rows="str.list" :columns="columns" row-key="id")
        template(#top-right)
            q-input(v-model="search" outlined dense type="text")
                template(#append)
                    q-icon(name="search")
        template(#body-cell-customer="props")
            td
                |{{ props.row.customer.name }}
        template(#body-cell-unit_code="props")
            td
                |{{ props.row.unit.code }}
        template(#body-cell-state="props")
            td
                q-chip(square :label="props.row.state" :class="props.row.state")

        template(#body-cell-options="props")
            td
                q-btn(icon="mdi-eye" class="mx-1" round size="sm" color="info" :to="$route.path + '/' + props.row.id") 
                q-btn(size="sm"  class="mx-1" round color="primary" icon="mdi-pencil" @click="editRealty(props.row)") 
                q-btn(size="sm" class="mx-1"  round color="negative" icon="mdi-close" @click="str.delete(props.row.id)")


    d-dialog(v-model="str.show_add_dialog" :title="t('titles.add_reservation')" @save="saveReservation()" :width="700")
        q-select(v-model="selected_customer" use-input option-label="name" option-value="id" :options="stp.customers" :label="t('inputs.customer')" :rules="[v => validate('required', v)]")
            template(#no-option)
                q-btn(:label="t('button.load_data')" block color="info" class="m-2" @click="stp.fetch('customer')")

        q-select(v-model="selected_unit" use-input option-label="code" option-value="id" :options="stu.list" :label="t('pages.realty_units', 2)" :rules="[v => validate('required', v)]")
            template(#no-option)
                q-btn(:label="t('button.load_data')" block color="info" class="m-2" @click="stu.fetch()")

        q-input(v-model="reservation.start_date" @click="st = !st" mask="date"  :label="t('inputs.start_date')" :rules="[v => validate('required ', v)]")
            template(#append)
                q-icon(name="event" class="cursor-pointer")
                    q-popup-proxy(v-model="st" :offset="[400, 0]")
                        q-date(v-model="reservation.start_date" minimal @update:modelValue="st = false")
        q-input(v-model="reservation.months_count" type="number" :label="t('inputs.months_count')" :rules="[v => validate('required ', v)]")
        q-input(v-model="reservation.rent_value_per_month" type="number" :label="t('inputs.rent_value_per_month')" :rules="[v => validate('required ', v)]")
        q-input(v-model="reservation.due_date" type="number" :label="t('inputs.due_date')" :rules="[v => validate('required ', v)]")



    //
        <a-dialog v-model="str.show_add_dialog" title="Add reservation" @save="saveReservation()">
        <a-form-item label="unit">
        <a-select placeholder="select unit" :show-arrow="false" :not-found-content="null" show-search
        :fieldNames="{ label: 'name', value: 'id' }" :options="str.realty_units"
        v-model:value="realty_reservation.realty_unit_id"></a-select>
        </a-form-item>
        <a-form-item label="customer">
        <a-select style="width: 100%" placeholder="select customer" :show-arrow="false"
        :not-found-content="null" show-search :fieldNames="{ label: 'name', value: 'id' }"
        :options="stc.customers" v-model:value="realty_reservation.customer_id"></a-select>
        </a-form-item>
        <a-form-item label="reservation start">
        <a-date-picker v-model:value="realty_reservation.start"></a-date-picker>
        </a-form-item>
        <a-form-item label="reservation end">
        <a-date-picker v-model:value="realty_reservation.end"></a-date-picker>
        </a-form-item>
        <a-form-item label="description">
        <a-textarea v-model:value="realty_reservation.description" :auto-size="{ minRows: 2, maxRows: 5 }">
        </a-textarea>
        </a-form-item>
        <a-form-item label="price">
        <a-input-number v-model:value="realty_reservation.price"></a-input-number>
        </a-form-item>
        <a-form-item label="files">
        <a-upload v-model:file-list="realty_reservation.docs" list-type="picture"
        action="http://realty.test/api/doc" @change="handleChange">
        <a-button>
        <upload-outlined></upload-outlined>
        upload
        </a-button>
        </a-upload>
        </a-form-item>
        {{ realty_reservation.docs }}

        </a-dialog>
</template>
