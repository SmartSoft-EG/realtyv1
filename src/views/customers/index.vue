<route lang="yaml">
name: العملاء
meta:
  auth: true
  group: admin
  roles: ['add','edit','delete']
</route>
<script setup lang="ts">
import { useCustomerStore } from '@/stores/customer';
import { pick } from '@/composables';

const cols = ['id', 'name', 'email', 'telephone']
const route = useRoute()
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: c,
        align: 'left',
    }
}))


const rows = computed(() => {
    const reg = new RegExp(`.*${data.search.split('').join('.*')}.*`)
    return stu.customers.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}

)


const stu = useCustomerStore()

const data = reactive({
    search: '',
    customer: {
        id: 0,
        name: '',
        email: '',
        telephone: '',
        password: '',
        job: '',
        address: '',
        national_id: ''
    },
})

onMounted(() => {
    stu.fetchCustomers()
})

function addCustomer() {
    data.customer.id = 0
    data.customer.name = ''
    stu.show_add_dialog = true
}

function filterData(rows: [], terms: string) {
    const reg = new RegExp(`.*${terms.split('').join('.*')}.*`)
    return rows.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}

function editUser(customer: any) {
    data.customer = pick(['id', 'name', 'email', 'telephone', 'address', 'job', 'national_id'], customer)
    stu.show_add_dialog = true
}

function saveCustomer() {
    stu.saveCustomer(data.customer)
}

</script>

<template>
    <d-page @refresh="stu.fetchCustomers()"><template #tools>
            <v-btn color="success" @click="addCustomer()">Add customer</v-btn>
        </template>

        <!-- <DataTable :value="rows" responsiveLayout="scroll" :paginator="true" :rows="10" س>

            <template #header>
                <InputText v-model="data.search" placeholder="Keyword Search" />
            </template>
            <Column v-for="col of columns" :field="col.field" :header="col.label" :sortable="true" :key="col.field">
            </Column>
            <Column header="job" bodyStyle="text-align: center" header-style="text-align: center">
                <template #body="slotProps">
                    <span class=" bg-sky-300 p-2 rounded-md">{{ slotProps.data.job }}</span>
                </template>
            </Column>
            <Column field="options" header="options">
                <template #body="prop">
                    <div class="flex gap-2">
                        <Button icon="pi pi-eye" class="p-button-rounded p-button-info"
                            @click="$router.push($route.path + '/' + prop.data.id)" />
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-warning"
                            @click="editUser(prop.data)" />
                        <Button icon="pi pi-times" class="p-button-rounded p-button-danger"
                            @click="stu.deleteCustomer(prop.data.id)" />
                    </div>
                </template>
            </Column>

        </DataTable> -->
        <d-dialog v-model="stu.show_add_dialog" @save="saveCustomer()">
            <q-input v-model="data.customer.name" type="text" label="name"></q-input>
            <q-input v-model="data.customer.email" type="text" label="email"></q-input>
            <q-input v-model="data.customer.telephone" type="text" label="telephone"></q-input>
            <q-input v-model="data.customer.job" type="text" label="job"></q-input>
            <q-input v-model="data.customer.address" type="text" label="address"></q-input>
            <q-input v-model="data.customer.national_id" type="text" label="national_id"></q-input>
        </d-dialog>
    </d-page>
</template>
