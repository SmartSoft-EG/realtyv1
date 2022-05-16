
<route lang="yaml">
meta:
 auth:
  roles: ['admin']
 group: 'accounts'
</route>

<script setup lang="ts">
import { useAccountStore } from '@/stores/account';
import axios from 'axios';

const sta = useAccountStore()

const columns = [
    {
        name: 'date',
        field: 'created_at',
        label: 'date',
    },
    {
        name: 'debit',
        field: 'debit',
        label: 'debit',
    },
    {
        name: 'credit',
        field: 'credit',
        label: 'credit',
    },
    {
        name: 'balance',
        field: 'balance',
        label: 'balance',
    },

]
onMounted(() => {
    sta.fetchAccounts()
})

const splitterModel = ref(50)
const selected = ref('Food')

const filter = ref('')
const active_node = ref({})

function selectedNode(target: number) {
    console.log(target)
    if (!target) return active_node.value = {}
    axios.get('account/' + target).then(res => active_node.value = res.data)
}
</script>


<template>
    <q-input ref="filterRef" filled v-model="filter" label="Filter" clearable></q-input>

    <q-splitter v-model="splitterModel" style="height: 800px">

        <template v-slot:before>
            <div class="q-pa-md">
                <q-tree @update:selected="selectedNode" :nodes="sta.accounts_tree" :filter="filter" node-key="id"
                    label-key="name" selected-color="primary" v-model:selected="selected" :default-expand-all="true" />
            </div>
        </template>

        <template v-slot:after>
            <vr-table v-show="active_node" :data="active_node"
                :headers="['id', 'name', 'is_main', 'debit', 'credit', 'balance', 'balance_type']">
            </vr-table>
            <q-table :rows="active_node.transactions" :columns="columns">

                <template #body-cell-balance="props">
                    <td>
                        {{ props.row.balance }}
                        <span
                            :class="{ 'bg-red-300': props.row.balance_type == 'credit', 'bg-green-300': props.row.balance_type == 'debit' }">
                            {{ props.row.balance_type }}</span>
                    </td>
                </template>

            </q-table>

        </template>
    </q-splitter>
</template>