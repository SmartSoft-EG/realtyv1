
<route lang="yaml">
name: account_origins
meta:
 auth:
  roles: ['admin']
 group: 'accounts'
</route>

<script setup lang="ts">
import { pick } from '@/composables';
import { useAccountOriginStore } from '@/stores/account_origin';
import axios from 'axios';
import * as $date from 'dayjs'

const sta = useAccountOriginStore()
const cols = ['id', 'name', 'debit', 'credit', 'balance', 'balance_type', 'options']

const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: t('inputs.' + c),
        align: 'left',
    }
}))


onMounted(() => {
    sta.fetch()
    setTimeout(() => {
        acc_tree.value?.expandAll()
    }, 1000);
})

const acc_tree = ref(null)
const selected = ref('')
const search = ref('')
const selected_range = ref([])
const active_account = ref({})
const show = ref(false)
const account = ref({ name: '', parent_id: '', is_main: 0 })
const filter = ref('')
const selected_account = ref('')
const { t } = useI18n()
const acc_accounts = ref([])
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

watch(selected_account, (new_val) => {
    if (!new_val) return
    axios.get('acc-account?parent_id=' + new_val).then(res => acc_accounts.value = res.data)
})


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


<template lang="pug">
d-page(@refresh="sta.fetch")
    template(#tools)
        q-btn(flat color="primary" @click="addAccount" icon="add" :label="t('general.add') + ' ' + t('general.accounts', 4)")
        el-button(v-if="selected" type="primary" @click="editAccount")
            | Edit Account
    .grid.grid-cols-12.gap-2
        div(class="col-span-12 sm:col-span-6 md:col-span-4")
            q-input(ref="filterRef" filled v-model="filter" label="Filter")
                template(v-slot:append)
                    q-icon.cursor-pointer(v-if="filter !== ''" name="clear")
            q-tree(ref="acc_tree" class="max-h-md  overflow-y-auto" v-model:selected="selected_account" :nodes="sta.tree" node-key="id" label-key="full_name" children-key="children" :filter="filter" default-expand-all selected-color="info")
        div(class="col-span-12 sm:col-span-6 md:col-span-8")
            q-table(:filter="search"  flat :rows="acc_accounts" :columns="columns" row-key="id")
                template(#top-right)
                    q-input(v-model="search" outlined dense type="text")
                        template(#append)
                            q-icon(name="search")
    .q-pa-md.q-gutter-sm
    .bg-light-700.p-2.flex.align-center Account :
        el-tree-select(label="Select account" placeholder="select account" v-model="selected" :data="sta.tree" node-key="id" @change="selectedNode" :props="{ label: 'name', children: 'children' }" filterable check-strictly)
        el-date-picker.bg-white(v-if="selected" v-model="selected_range" type="datetimerange" range-separator="~" start-placeholder="Start" end-placeholder="End" format="DD-MM-YYYY hh:mm:ss a" @change="fetchDetails")
        span selected
    vr-table(v-show="active_account" :data="active_account" :headers="['id', 'name', 'is_main', 'debit', 'credit', 'balance', 'balance_type']")
    el-table(:data="active_account.transactions" style="width: 100%" stripe)
        el-table-column(prop="created_at" label="Date" width="180")
            template(#default="scope")
                | {{ $date(scope.row.created_at).format('DD-MM-YYYY hh:mm:ss a') }}
        el-table-column(prop="debit" label="debit")
        el-table-column(prop="credit" label="credit")
        el-table-column.bg-red-200(prop="balance" label="balance")
            template(#default="scope")
                span(style="display: inline-block;" :class="[{ 'bg-red-300': scope.row.balance_type == 'credit', 'bg-green-300': scope.row.balance_type == 'debit' }, 'px-2']")
                    | {{ parseFloat(scope.row.balance) }} : {{ scope.row.balance_type }}
    a-dialog(v-model="show" :formData="account" @save="saveAccount" style="max-width:400px")
        el-form-item(prop="parent_id" label="parent account" :rules="{ required: true }")
            el-tree-select(label="Select account" style="width:100%" placeholder="select account" v-model="account.parent_id" :data="makTree(sta.main_accounts, 1)" node-key="id" :props="{ label: 'name', children: 'children' }" filterable check-strictly)
        el-form-item(prop="name" label="name" :rules="{ required: true }")
            el-input(v-model="account.name")
        el-form-item(prop="is_main" label="Main account" :rules="{ required: true }")
            el-switch(v-model="account.is_main")
        el-form-item(prop="debit_or_credit" label="balance type" :rules="{ required: true }")
            el-radio(v-model="account.debit_or_credit" value="debit" label="debit")
            el-radio(v-model="account.debit_or_credit" value="credit" label="credit")
</template>