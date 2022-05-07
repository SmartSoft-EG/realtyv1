
<route lang="yaml">
meta: 
    auth: true
    group: الحجوزات
</route>

<script setup lang="ts">
import { useRealtyStore } from '@/stores/realty'
import type { RealtyUnit } from '@/composables'
import { pick } from '@/composables'
import { serialize } from 'object-to-formdata';
import { useCustomerStore } from '@/stores/customer';
import { DateTime } from 'luxon';
import type { UploadChangeParam, UploadProps } from 'ant-design-vue';

const cols = ['id', 'name', 'unite', 'customer', 'start', 'end', 'address', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: c,
        align: 'left',
    }
}))

const str = useRealtyStore()
const stc = useCustomerStore()

onMounted(() => {
    if (str.realty_units.length == 0) str.fetchRealtyUnits()
    if (str.realty_reservations.length == 0) str.fetchRealtyReservations()
    if (stc.customers.length == 0) stc.fetchCustomers()
})

const realty_reservation = ref({
    id: 0,
    realty_unit_id: 0,
    customer_id: 0,
    start: '2019/02/01',
    end: '',
    duration: [],
    price: 0,
    state: 'active',
    description: '',
})

const search = ref('')
const imgs = ref([])

function addReservation() {
    realty_reservation.value = {
        id: 0,
        realty_unit_id: 0,
        customer_id: 0,
        start: '',
        end: '',
        duration: { from: '', to: '' },
        docs: [],
        price: 0,
        state: 'active',
        description: '',
    }
    str.show_add_dialog = true
}


// function editRealty(rt: Realty) {
//     realty.value = rt
//     str.show_add_dialog = true
// }

function saveReservation() {

    str.saveReservation(realty_reservation.value)
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

<template>
    <d-page @refresh="str.fetchRealty()"><template #tools>
            <v-btn color="primary" @click="addReservation" v-text="'Add reservation'"></v-btn>
        </template>
        <q-table title="Realty units" :filter="search" flat :rows="str.realty_reservations" :columns="columns"
            row-key="id"><template #top-right>
                <q-input v-model="search" outlined dense type="text"><template #append>
                        <q-icon name="search"></q-icon>
                    </template></q-input>
            </template><template #body-cell-options="props">
                <td class="flex gap-2">
                    <v-btn icon="mdi-eye" variant="outlined" density="compact" color="success"
                        :to="$route.path + '/' + props.row.id"></v-btn>
                    <v-btn icon="mdi-pencil" variant="outlined" density="compact" color="primary"
                        @click="editRealty(props.row)"></v-btn>
                    <v-btn icon="mdi-close" variant="outlined" density="compact" color="error"
                        @click="deleteRealty(props.row.id)"></v-btn>
                </td>
            </template></q-table>


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


    </d-page>
</template>
