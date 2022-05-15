<route lang="yaml">
name: العملاء
meta:
  auth: true
  group: 'admin'
  roles: ['add','edit','delete']
</route>
<script setup lang="ts">
import { useCustomerStore } from '@/stores/customer';
import { pick } from '@/composables';
const search = ref('')
const cols = ['id', 'name', 'account', 'email', 'telephone', 'options']
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
const rules =
{
    name: [
        { required: true, message: 'Please input  name', trigger: 'blur' },
    ],
}

function addCustomer() {
    data.customer.id = 0
    data.customer.name = ''
    stu.show_add_dialog = true
}

function filterData(rows: [], terms: string) {
    const reg = new RegExp(`.*${terms.split('').join('.*')}.*`)
    return rows.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}

function editCustomer(customer: any) {
    data.customer = pick(['id', 'name', 'email', 'telephone', 'address', 'job', 'national_id'], customer)
    stu.show_add_dialog = true
}

function deleteCustomer(id: number) {

    stu.deleteCustomer(id)
}

function saveCustomer() {
    //console.log('save')
    stu.saveCustomer(data.customer)
}

</script>

<template>
    <d-page @refresh="stu.fetchCustomers()"><template #tools>
            <v-btn color="success" @click="addCustomer()">Add customer</v-btn>
        </template>
        <q-table title="Customers" :filter="search" flat :rows="stu.customers" :columns="columns" row-key="id"><template
                #top-right>
                <q-input v-model="search" outlined dense type="text"><template #append>
                        <q-icon name="search"></q-icon>
                    </template>
                </q-input>


            </template>

            <template #body-cell-account="props">
                <td class="flex gap-2">
                    {{ props.row.account.id }}

                </td>
            </template>
            <template #body-cell-options="props">
                <td class="flex gap-2">
                    <v-btn icon="mdi-eye" variant="outlined" density="compact" color="success"
                        :to="'/customers/' + props.row.id"></v-btn>
                    <v-btn icon="mdi-pencil" variant="outlined" density="compact" color="primary"
                        @click="editCustomer(props.row)"></v-btn>

                    <a-popconfirm title="Are you sure delete this customer?" ok-text="Yes" cancel-text="No"
                        @confirm="deleteCustomer(props.row.id)">
                        <v-icon size="large" href="#delete">mdi-close</v-icon>
                    </a-popconfirm>

                </td>
            </template>
        </q-table>
        <!--  d-dialog(v-model="stu.show_add_dialog" @save="saveCustomer()") -->
        <!--      q-input(v-model="data.customer.name" type="text" label="name") -->
        <!--      q-input(v-model="data.customer.email" type="text" label="email") -->
        <!--      q-input(v-model="data.customer.telephone" type="text" label="telephone") -->
        <!--      q-input(v-model="data.customer.job" type="text" label="job") -->
        <!--      q-input(v-model="data.customer.address" type="text" label="address") -->
        <!--      q-input(v-model="data.customer.national_id" type="text" label="national_id") -->

        <a-dialog v-model="stu.show_add_dialog" @save="saveCustomer()" width="600px" :formData="data.customer"
            :title="data.customer.id ? 'Edit customer' : 'Add customer'">
            <a-form-item label="name" name="name" focus :rules="{ required: true, message: 'please add name' }">
                <a-input v-model:value="data.customer.name"></a-input>
            </a-form-item>

            <a-form-item label="email" name="email"
                :rules="{ required: true, type: 'email', message: 'please add valid email' }">
                <a-input v-model:value="data.customer.email"></a-input>
            </a-form-item>

            <a-form-item label="telephone" name="telephone" :rules="[
            { required: true, min: 10, message: 'please add valid telephone' },
            { max: 10, message: 'telephone should be 10 digit' }]">
                <a-input type="number" v-model:value="data.customer.telephone"></a-input>
            </a-form-item>

            <a-form-item label="address" name="address" :rules="{ required: true, message: 'please add address' }">
                <a-input v-model:value="data.customer.address"></a-input>
            </a-form-item>

            <a-form-item label="job" name="job" :rules="{ required: true, message: 'please add job' }">
                <a-textarea v-model:value="data.customer.job"></a-textarea>
            </a-form-item>
        </a-dialog>
    </d-page>
</template>
