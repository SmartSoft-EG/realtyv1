
<route lang="yaml">
name: reservation_details
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">
import * as $date from 'dayjs'
import type { Reservation } from '@/composables'
import axios from 'axios';
import { serialize } from 'object-to-formdata';
import useValidation from '@/composables/validations';
import { usePeopleStore } from '@/stores/peoples';
const g = getCurrentInstance()?.proxy
const { t } = useI18n()
const route = useRoute()
const stp = usePeopleStore()

const { validate } = useValidation()

const reservation = ref<Reservation>({ id: 0, transactions: [] })
const imgs = ref([])
const test = ref(true)
const show_add_dialog = ref(false)
const show_add_reservation = ref(false)
onMounted(() => {

    fetchReservation()
})

const st = ref(false)
const selected_customer = ref('')
const tab = ref('details')
const due_transactions = computed(() => reservation.value.transactions.filter(r => r.type == 'due'))
const payment_transactions = computed(() => reservation.value.transactions.filter(r => r.type == 'payment'))

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

function fetchReservation() {
    axios.get('realty-unit-reservation/' + route.params.id).then(res => reservation.value = res.data)
}
function saveReservation() {

    reservation.value.customer_id = selected_customer.value.id
    reservation.value.end_date = $date(reservation.value.start_date).add(reservation.value.months_count, 'months').format('YYYY/MM/DD')
    axios.post('realty-unit-reservation', reservation.value).then(res => {

    })



}

function performTransaction(transaction_id: number, type: string) {
    axios.put('acc-transaction/' + transaction_id, { _type: type }).then(res => {
        fetchReservation()
    })
}

function optionsFn(date) {
    if (!reservation.value.start_date) return
    return date >= reservation.value.start_date
}

</script>

<template>
    <d-page>
        <template #tools>
            <q-btn color="primary" icon-color="info" icon="edit" @click=""
                :label="t('button.edit') + ' ' + t('pages.reservations', 2)"></q-btn>
        </template>

        <q-tabs v-model="tab" align="left" :breakpoint="0">
            <q-tab name="details" :label="t('general.details')" />
            <q-tab name="dues" :label="t('general.dues')" />
            <q-tab name="payments" :label="t('general.payments')" />

        </q-tabs>

        <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="details">
                <h5 class="my-4">
                    <q-icon class="mx-2" name="circle" color="info"></q-icon>{{ t('general.details') }}
                </h5>
                <vr-table :data="reservation"
                    :headers="['id', 'unit_code|unit.code', 'start_date', 'end_date', 'rent_value_per_month', 'months_count', 'total_dues', 'total_payments', 'customer|customer.name', 'state', 'description', 'docs', 'progress']">
                    <template #docs>
                        <div class="flex center justify-center gap-2">
                            <el-image style="height:100px" v-for="(doc, i) in reservation.docs" :key="i"
                                :src="$storage + doc.path" alt="Image" preview height="120" width="180"></el-image>
                        </div>
                    </template>
                    <template #progress>
                        <q-linear-progress size="25px" :value="reservation.progress" color="accent" class="q-mt-sm">
                            <div class="absolute-full flex flex-center">
                                <q-badge color="white" text-color="accent" :label="reservation.progress * 100 + ' %'" />
                            </div>
                        </q-linear-progress>

                    </template>
                </vr-table>
            </q-tab-panel>

            <q-tab-panel name="dues">
                <h5 class="my-4">
                    <q-icon class="mx-2" name="circle" color="info"></q-icon>{{ t('general.dues') }}
                </h5>
                <el-table class="mt-4" :data="due_transactions" style="width: 100%" stripe>
                    <el-table-column prop="date" :label="t('inputs.due_date')"></el-table-column>
                    <el-table-column prop="description" :label="t('inputs.description')" width="280"></el-table-column>
                    <el-table-column prop="value" :label="t('inputs.value')"></el-table-column>
                    <el-table-column prop="state" :label="t('inputs.state')"><template #default="scope">
                            <q-chip square :label="t('general.' + scope.row.state)" :class="scope.row.state"></q-chip>
                        </template></el-table-column>
                    <el-table-column prop="options" :label="t('inputs.options')"><template #default="scope">
                            <q-btn v-show="scope.row.state == 'waiting'" size="md" :label="t('general.dues', 2)"
                                color="info" @click="performTransaction(scope.row.id, 'apply_due')"></q-btn>

                        </template></el-table-column>
                </el-table>
            </q-tab-panel>

            <q-tab-panel name="payments">
                <h5 class="my-4">
                    <q-icon class="mx-2" name="circle" color="info"></q-icon>{{ t('general.payments') }}
                </h5>
                <el-table class="mt-4" :data="payment_transactions" style="width: 100%" stripe>
                    <el-table-column prop="date" :label="t('inputs.due_date')"></el-table-column>
                    <el-table-column prop="description" :label="t('inputs.description')" width="280"></el-table-column>
                    <el-table-column prop="value" :label="t('inputs.value')"></el-table-column>
                    <el-table-column prop="state" :label="t('inputs.state')"><template #default="scope">
                            <q-chip square :label="t('general.' + scope.row.state)" :class="scope.row.state"></q-chip>
                        </template></el-table-column>
                    <el-table-column prop="options" :label="t('inputs.options')"><template #default="scope">
                            <q-btn v-show="scope.row.state == 'waiting'" size="md" :label="t('general.payments', 2)"
                                color="info" @click="performTransaction(scope.row.id, 'apply_payment')"></q-btn>
                        </template></el-table-column>
                </el-table>

            </q-tab-panel>
        </q-tab-panels>






        <d-dialog v-model="show_add_dialog" :title="t('button.edit') + ' ' + t('pages.realty_units', 2)"
            @save="updateRealty()">
            <q-select outline v-model="realty_unit.type" :options="['apartment', 'market']" :label="t('inputs.type')"
                :rules="[v => validate('required', v)]"></q-select>
            <q-input v-model="realty_unit.floor" type="number" :label="t('inputs.floor')"
                :rules="[v => validate('required|max:3', v)]"></q-input>
            <q-input v-model="realty_unit.size" type="number" :label="t('inputs.size')"
                :rules="[v => validate('required', v)]"></q-input>
            <q-input size="sm" v-model="realty_unit.code" type="text" :label="t('inputs.code')"
                :rules="[v => validate('required|max:6', v)]"></q-input>
            <q-file v-model="imgs" multiple :label="t('inputs.docs')"></q-file>
            <q-input v-model="realty_unit.description" type="textarea" :label="t('inputs.description')"
                :rules="[v => validate('required', v)]"></q-input>
        </d-dialog>
        <d-dialog v-model="show_add_reservation" :title="t('titles.add_reservation')" @save="saveReservation()"
            :width="700">
            <q-select v-model="selected_customer" use-input option-label="name" option-value="id"
                :options="stp.customers" :label="t('inputs.customer')" :rules="[v => validate('required', v)]"><template
                    #no-option>
                    <q-btn class="m-2" :label="t('button.load_data')" block color="info" @click="stp.fetch('customer')">
                    </q-btn>
                </template></q-select>
            <q-input v-model="reservation.start_date" @click="st = !st" mask="date" :label="t('inputs.start_date')"
                :rules="[v => validate('required ', v)]"><template #append>
                    <q-icon class="cursor-pointer" name="event">
                        <q-popup-proxy v-model="st" :offset="[400, 0]">
                            <q-date v-model="reservation.start_date" minimal @update:modelValue="st = false"></q-date>
                        </q-popup-proxy>
                    </q-icon>
                </template></q-input>
            <q-input v-model="reservation.months_count" type="number" :label="t('inputs.months_count')"
                :rules="[v => validate('required ', v)]"></q-input>
            <q-input v-model="reservation.rent_value_per_month" type="number" :label="t('inputs.rent_value_per_month')"
                :rules="[v => validate('required ', v)]"></q-input>
            <q-input v-model="reservation.due_date" type="number" :label="t('inputs.due_date')"
                :rules="[v => validate('required ', v)]"></q-input>
        </d-dialog>
    </d-page>
</template>
<style>
</style>
