<route lang="yaml">
name: objects
meta:
  auth: true
  group: 'admin'
  roles: ['add','edit','delete']
</route>
<script setup lang="ts">
import { useObjectStore } from '@/stores/object';
import { pick } from '@/composables';
import useValidation from '@/composables/validations';

const cols = ['id', 'name', 'type', 'email', 'telephone', 'address', 'balance', 'options']

const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: t('inputs.' + c),
        align: 'left',
    }
}))


const rows = computed(() => {
    const reg = new RegExp(`.*${search.split('').join('.*')}.*`)
    return stO.list.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}
)

const { t } = useI18n()
const selected_type = ref('')
const stO = useObjectStore()
const search = ref('')
const people = ref({
    id: 0,
    type: '',
    name: '',
    email: '',
    telephone: '',
    job: '',
    address: '',
    national_id: ''
})

const { validate } = useValidation()
onMounted(() => {
    stO.fetchAll()
})

function addPeople() {
    people.value.id = 0
    people.value.name = ''
    stO.show_add_dialog = true
}

function filterData(rows: [], terms: string) {
    const reg = new RegExp(`.*${terms.split('').join('.*')}.*`)
    return rows.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}

function editPeople(data: any) {
    people.value = pick(['id', 'name', 'type', 'email', 'telephone', 'address', 'job', 'national_id'], data)
    stO.show_add_dialog = true
}

function deletePeople(id: number) {

    stO.delete(id)
}

function savePeople() {
    //console.log('save')
    stO.save(people.value)
}

</script>

<template lang="pug">
d-page(@refresh="stO.fetch('customers')")
    template(#tools)
        q-btn(flat color="primary" @click="addPeople" icon="mdi-plus" :label="t('button.add') + ' ' + t('pages.objects', 2)")

    q-table(:title="t('pages.peoples', 1)" :filter="search"  flat :rows="stO.type(selected_type)" :columns="columns" row-key="id")
        template(#top-right)

            q-input(v-model="search" outlined dense type="text")
                template(#append)
                    q-icon(name="search")
            q-select(outlined class="w-30" dense v-model="selected_type" :options="['customer', 'supplier', 'owner', 'employee']") 
        template(#body-cell-type="props")
            td
                el-tag {{ t('general.' + props.row.type) }}
        template(#body-cell-options="props")
            td.flex.items-center.gap-2
                q-btn(icon="mdi-eye" round size="sm" color="info" :to="'/peoples/' + props.row.id")
                q-btn(icon="mdi-pencil" size="sm" round  color="primary" @click="editPeople(props.row)")
                el-popconfirm(title="Are you sure to delete this?" @confirm="deletePeople(props.row.id)")
                    template(#reference)
                        q-btn(icon="close" size="sm" round  color="negative" href="#delete")

    d-dialog(v-model="stO.show_add_dialog" @save="savePeople()")
        q-select(v-model="people.type" :label="t('inputs.type')" :options="['customer', 'supplier', 'owner']" :rules="[v => validate('required', v)]")
        q-input(v-model="people.name" type="text" :label="t('inputs.name')" :rules="[v => validate('required', v)]")
        q-input(v-model="people.email" type="email" :label="t('inputs.email')" )
        q-input(v-model="people.telephone" type="number" :label="t('inputs.telephone')" :rules="[v => validate('required|digits:11', v)]")
        q-input(v-model="people.job" type="text" :label="t('inputs.job')")
        q-input(v-model="people.address" type="text" :label="t('inputs.address')")
        q-input(v-model="people.national_id" type="text" :label="t('inputs.national_id')" )

</template>
