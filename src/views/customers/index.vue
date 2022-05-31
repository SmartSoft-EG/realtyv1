<route lang="yaml">
name: customers
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
    return stu.list.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
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
            <q-btn color="success" @click="addCustomer()">Add customer</q-btn>
        </template>
        <q-table title="Customers" :filter="search" flat :rows="stu.list" :columns="columns" row-key="id"><template
                #top-right>
                <q-input v-model="search" outlined dense type="text"><template #append>
                        <q-icon name="search"></q-icon>
                    </template></q-input>
            </template><template #body-cell-account="props">
                <td>
                    <el-tag>{{ props.row.account ? props.row.account.id : '' }}</el-tag>
                </td>
            </template><template #body-cell-options="props">
                <td class="flex gap-2">
                    <q-btn icon="mdi-eye" variant="outlined" density="compact" color="success"
                        :to="'/customers/' + props.row.id"></q-btn>
                    <q-btn icon="mdi-pencil" variant="outlined" density="compact" color="primary"
                        @click="editCustomer(props.row)"></q-btn>
                    <el-popconfirm title="Are you sure to delete this?" @confirm="deleteCustomer(props.row.id)">
                        <template #reference>
                            <v-icon size="large" href="#delete">mdi-close</v-icon>
                        </template>
                    </el-popconfirm>
                </td>
            </template></q-table>
        <d-dialog v-model="stu.show_add_dialog" @save="saveCustomer()">
            <q-input v-model="data.customer.name" type="text" label="name"
                :rules="[v => !!v || 'this item is required']"></q-input>
            <q-input v-model="data.customer.email" type="text" label="email"
                :rules="['email' || 'please write valide email',]"></q-input>
            <q-input v-model="data.customer.telephone" type="text" label="telephone"
                :rules="[v => !!v || 'this item is required']"></q-input>
            <q-input v-model="data.customer.job" type="text" label="job" :rules="[v => !!v || 'this item is required']">
            </q-input>
            <q-input v-model="data.customer.address" type="text" label="address"
                :rules="[v => !!v || 'this item is required']"></q-input>
            <q-input v-model="data.customer.national_id" type="text" label="national_id"
                :rules="[v => !!v || 'this item is required']"></q-input>
        </d-dialog>
        <!--  a-dialog(v-model="stu.show_add_dialog" @save="saveCustomer()" :formData="data.customer" :title="data.customer.id ? 'Edit customer' : 'Add customer'") -->
        <!--      el-form-item(label="name" prop="name" focus :rules="{ required: true, message: 'please add name' }") -->
        <!--          el-input(v-model="data.customer.name") -->
        <!--      el-form-item(label="email" prop="email" :rules="{ required: true, type: 'email', message: 'please add valid email' }") -->
        <!--          el-input(v-model="data.customer.email") -->
        <!--      el-form-item(label="telephone" prop="telephone" :rules=`[ -->
        <!--          { required: true, min: 10, message: 'please add valid telephone' }, -->
        <!--          { max: 10, message: 'telephone should be 10 digit' }]`) -->
        <!--          el-input(type="number" v-model="data.customer.telephone") -->
        <!--      el-form-item(label="address" prop="address" :rules="{ required: true, message: 'please add address' }") -->
        <!--          el-input(v-model="data.customer.address") -->
        <!--      el-form-item(label="job" prop="job" :rules="{ required: true, message: 'please add job' }") -->
        <!--          el-input(v-model="data.customer.job") -->
    </d-page>
</template>
