
<route lang="yaml">
meta:
 auth:
  roles: ['admin']
 group: 'accounts'
</route>

<script setup lang="ts">
import { pick } from '@/composables';
import { useAccountStore } from '@/stores/account';
import axios from 'axios';
import * as $date from 'dayjs'

const sta = useAccountStore()



onMounted(() => {
    sta.fetchAccounts()
})

const selected = ref('')
const selected_range = ref([])
const active_account = ref({})
const show = ref(false)
const account = ref({ name: '', parent_id: '', is_main: 0 })

function selectedNode(target: any) {
    console.log(target)
    if (!target) return active_account.value = {}
    axios.get('account/' + selected.value).then(res => active_account.value = res.data)
}

function fetchDetails() {
    if (!selected.value) return
    const params = {
        by_account_range: true,
        account_id: selected.value,
        start: $date(selected_range.value[0]).format('YYYY-MM-DD HH:mm:ss'),
        end: $date(selected_range.value[1]).format('YYYY-MM-DD HH:mm:ss')
    }
    axios.get('acc-trans-detail', { params }).then(res => active_account.value.transactions = res.data)
}



function addAccount() {
    show.value = true
}


function saveAccount() {
    axios.post('account', account.value)

}

function makTree(nodes: any, parentId: number) {
    return nodes
        .filter((node) => node.parent_id === parentId)
        .reduce(
            (tree, node) => [
                ...tree,
                {
                    ...node,
                    children: makTree(nodes, node.id),
                },
            ],
            [],
        )
}

function editAccount() {
    if (!active_account.value) return

    account.value = pick(['id', 'name', 'parent_id', 'is_main', 'debit_or_credit'], active_account.value)
    show.value = true
}

</script>


<template>
    <d-page @refresh="sta.fetchAccounts">
        <template #tools>
            <el-button type="primary" @click="addAccount">Add Account </el-button>
            <el-button v-if="selected" type="primary" @click="editAccount">Edit Account </el-button>

        </template>

        <div class="bg-light-700 p-2 flex align-center">
            Account :
            <el-tree-select label="Select account" placeholder="select account" v-model="selected"
                :data="makTree(sta.accounts, 1)" node-key="id" @change="selectedNode"
                :props="{ label: 'name', children: 'children' }" filterable check-strictly />
            <el-date-picker class="bg-white" v-if="selected" v-model="selected_range" type="datetimerange"
                range-separator="~" start-placeholder="Start" end-placeholder="End" format="DD-MM-YYYY hh:mm:ss a"
                @change="fetchDetails" />
            <span>selected</span>
        </div>

        <vr-table v-show="active_account" :data="active_account"
            :headers="['id', 'name', 'is_main', 'debit', 'credit', 'balance', 'balance_type']">
        </vr-table>

        <el-table :data="active_account.transactions" style="width: 100%" stripe>
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

        <a-dialog v-model="show" :formData="account" @save="saveAccount" style="max-width:400px">

            <el-form-item prop="parent_id" label="parent account" :rules="{ required: true }">
                <el-tree-select label="Select account" style="width:100%" placeholder="select account"
                    v-model="account.parent_id" :data="makTree(sta.main_accounts, 1)" node-key="id"
                    :props="{ label: 'name', children: 'children' }" filterable check-strictly />
            </el-form-item>

            <el-form-item prop="name" label="name" :rules="{ required: true }">
                <el-input v-model="account.name"></el-input>
            </el-form-item>

            <el-form-item prop="is_main" label="Main account" :rules="{ required: true }">
                <el-switch v-model="account.is_main"></el-switch>
            </el-form-item>

            <el-form-item prop="debit_or_credit" label="balance type" :rules="{ required: true }">
                <el-radio v-model="account.debit_or_credit" value="debit" label="debit"></el-radio>
                <el-radio v-model="account.debit_or_credit" value="credit" label="credit"></el-radio>

            </el-form-item>

        </a-dialog>
    </d-page>
</template>