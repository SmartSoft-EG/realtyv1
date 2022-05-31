
<route lang="yaml">
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">

import axios from 'axios';
import * as $date from 'dayjs'

//import { serialize } from 'object-to-formdata';
//const g = getCurrentInstance()?.proxy
const route = useRoute()

const owner = ref({
    id: 0,
    name: '',
    address: '',
    account: {}
})
const owner_account_transactions = ref([])
const selected_range = ref([])

//const show_add_dialog = ref(false)

onMounted(() => {
    fetchOwner()
})

function fetchOwner() {
    axios.get('owner/' + route.params.id).then(res => owner.value = res.data)
}


function editCustomer() {


}

function fetchDetails() {

    const params = {
        by_account_range: true,
        account_id: owner.value.account.id,
        start: $date(selected_range.value[0]).format('YYYY-MM-DD HH:mm:ss'),
        end: $date(selected_range.value[1]).format('YYYY-MM-DD HH:mm:ss')
    }
    axios.get('acc-trans-detail', { params }).then(res => owner_account_transactions.value = res.data)
}

function addTrans() {
    axios.post('test', { type: 'debit', balance: 2000, account_id: owner.value.account.id });
}
</script>

<template>
    <d-page @refresh="fetchOwner()"><template #tools>
            <q-btn color="primary" @click="editCustomer()" v-text="'Edit owner'"></q-btn>
            <el-button @click="addTrans">add transaction test</el-button>
            <el-date-picker class="bg-white" v-model="selected_range" type="datetimerange" range-separator="~"
                start-placeholder="Start" end-placeholder="End" format="DD-MM-YYYY hh:mm:ss a" @change="fetchDetails" />
        </template>
        <vr-table :data="owner"
            :headers="['id', 'name', 'address', 'telephone', 'job', 'account_id|account.id', 'account|account.name', 'balance|account.balance', 'balance_type|account.balance_type']">
        </vr-table>

        <el-table :data="owner_account_transactions" style="width: 100%" stripe>
            <el-table-column prop="created_at" label="Date" width="180">
                <template #default="scope">
                    {{ $date(scope.row.created_at).format('DD-MM-YYYY hh:mm:ss a') }}
                </template>
            </el-table-column>
            <el-table-column prop="debit" label="debit" />
            <el-table-column prop="credit" label="credit" />
            <el-table-column prop="balance" label="balance" class="bg-red-200">
                <template #default="scope">
                    <span style="display: inline-block;"
                        :class="[{ 'bg-red-300': scope.row.balance_type == 'credit', 'bg-green-300': scope.row.balance_type == 'debit' }, 'px-2']">
                        {{ parseFloat(scope.row.balance) }} : {{ scope.row.balance_type }}
                    </span>
                </template>
            </el-table-column>
        </el-table>
    </d-page>



    <!--    d-dialog(v-model="show_add_dialog" title="Add owner" @save="updateRealty()") -->
    <!--      q-input(v-model="owner.name" type="text" label="name") -->
    <!--      q-select(v-model="owner.type" :options="['t1', 't2', 't3']" label="type") -->
    <!--      q-input(v-model="owner.code" type="text" label="code") -->
    <!--      q-input(v-model="owner.floor" type="text" label="floor") -->
    <!--      q-input(v-model="owner.description" type="textarea" label="description") -->
    <!--      q-file(v-model="imgs" multiple label="files") -->
</template>
