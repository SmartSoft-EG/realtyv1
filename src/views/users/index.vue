<route lang="yaml">
meta:
  auth: true
  group: admin
  roles: ['add','edit','delete']
</route>
<script setup lang="ts">
import { useUserStore } from '../../stores/user'
const { t } = useI18n()
const cols = ['id', 'name', 'email', 'telephone', 'job', 'options']
const columns = computed(() => cols.map((c) => {
  return {
    name: c,
    field: c,
    label: t('inputs.' + c),
    align: 'left',
  }
}))

const stu = useUserStore()
const data = reactive({
  show_add_user: false,
  search: '',
  user: {
    id: 0,
    name: '',
    email: '',
    telephone: '',
    password: '',
    job: '',
  },
})
onMounted(() => {
  stu.fetchUsers()
})

function addUser() {
  data.user.id = 0
  data.user.name = ''
  stu.show_add_dialog = true
}

function filterData(rows, terms) {
  const reg = new RegExp(`.*${terms.split('').join('.*')}.*`)
  return rows.filter(e => reg.test(e.name.toLowerCase()) || reg.test(e.telephone) || reg.test(e.email))
}

function editUser(user) {
  data.user = (({ id, name, email, telephone, job, password }) => ({ id, name, email, telephone, job, password }))(user)
  data.user.password = ''
  stu.show_add_dialog = true
}
function saveUser() {
  // console.log(data.user)
  stu.saveUser(data.user)
}
</script>

<template lang="pug">
d-page(@refresh="stu.fetchUsers()")
  template(#tools)
    q-btn(color="primary" icon="add" flat @click="addUser()" :label="t('button.add') + ' ' + t('pages.users', 2)")

  q-table(:filter-method="filterData" :filter="data.search" title="Users" flat :rows="stu.users" :columns="columns" row-key="id" :loading="stu.loading")
    template(#top-right)
      q-input(v-model="data.search" outlined dense type="text")
        template(#append)
          q-icon(name="search")
          q-icon.clickable(name="refresh" @click="stu.fetchUsers()")
    template(#body-cell-options="props")
      td
        q-btn(icon="mdi-eye" class="mx-1" size="sm" round color="positive" :to="$route.path + '/' + props.row.id")
        q-btn(icon="mdi-pencil" round class="mx-1" size="sm" color="primary" @click="editUser(props.row)")

  d-dialog(v-model="stu.show_add_dialog" @save="saveUser()" :title="t('button.add') + ' ' + t('pages.users', 2)")
    q-input(v-model="data.user.name" type="text" :label="t('inputs.name')")
    q-input(v-model="data.user.email" type="text" :label="t('inputs.email')")
    q-input(v-model="data.user.password" type="text" :label="t('inputs.password')")
    q-input(v-model="data.user.telephone" type="text" :label="t('inputs.telephone')")
    q-input(v-model="data.user.job" type="text" :label="t('inputs.job')")
</template>
