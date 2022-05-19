<route lang="yaml">
name: الملاك
meta:
  auth: true
  group: 'admin'
  roles: ['add','edit','delete']
</route>
<script setup lang="ts">
import { useOwnerStore } from '@/stores/owners';
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
    return stu.owners.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}

)


const stu = useOwnerStore()

const data = reactive({
    search: '',
    owner: {
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
    stu.fetch()
})


function addOwner() {
    data.owner.id = 0
    data.owner.name = ''
    stu.show_add_dialog = true
}



function editOwner(owner: any) {
    data.owner = pick(['id', 'name', 'email', 'telephone', 'address', 'job', 'national_id'], owner)
    stu.show_add_dialog = true
}

function deleteCustomer(id: number) {

    stu.delete(id)
}

function saveOwner() {
    //console.log('save')
    stu.save(data.owner)
}

</script>

<template>
    <d-page @refresh="stu.fetch()"><template #tools>
            <v-btn color="success" @click="addOwner()">Add owner</v-btn>
        </template>

        <q-table title="owners" :filter="search" flat :rows="stu.owners" :columns="columns" row-key="id"><template
                #top-right>
                <q-input v-model="search" outlined dense type="text"><template #append>
                        <q-icon name="search"></q-icon>
                    </template>
                </q-input>


            </template>

            <template #body-cell-account="props">
                <td>
                    <el-tag> {{ props.row.account ? props.row.account.id : '' }}</el-tag>
                </td>
            </template>

            <template #body-cell-options="props">
                <td class="flex gap-2">
                    <v-btn icon="mdi-eye" variant="outlined" density="compact" color="success"
                        :to="'/owners/' + props.row.id"></v-btn>
                    <v-btn icon="mdi-pencil" variant="outlined" density="compact" color="primary"
                        @click="editOwner(props.row)"></v-btn>
                    <el-popconfirm title="Are you sure to delete this?" @confirm="deleteCustomer(props.row.id)">
                        <template #reference>
                            <v-icon size="large" href="#delete">mdi-close</v-icon>
                        </template>
                    </el-popconfirm>


                </td>
            </template>
        </q-table>

        <a-dialog v-model="stu.show_add_dialog" @save="saveOwner()" :formData="data.owner"
            :title="data.owner.id ? 'Edit owner' : 'Add owner'">

            <el-form-item label="name" prop="name" focus :rules="{ required: true, message: 'please add name' }">
                <el-input v-model="data.owner.name"></el-input>
            </el-form-item>

            <el-form-item label="telephone" prop="telephone" :rules="[
            { required: true, min: 10, message: 'please add valid telephone' },
            { max: 10, message: 'telephone should be 10 digit' }]">
                <el-input type="number" v-model="data.owner.telephone"></el-input>
            </el-form-item>

            <el-form-item label="address" prop="address">
                <el-input v-model="data.owner.address"></el-input>
            </el-form-item>

            <el-form-item label="job" prop="job">
                <el-input v-model="data.owner.job"></el-input>
            </el-form-item>
        </a-dialog>
    </d-page>
</template>
