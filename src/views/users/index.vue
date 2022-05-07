<route lang="yaml">
meta:
  auth: true
  group: admin
  roles: ['add','edit','delete']
</route>
<script setup lang="ts">
import { useUserStore } from '../../stores/user'
const cols = ['id', 'name', 'email', 'telephone', 'job', 'options']
const columns = computed(() => cols.map((c) => {
  return {
    name: c,
    field: c,
    label: c,
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
    v-btn(color="success" @click="addUser()")
      | Add user
  q-table(:filter-method="filterData" :filter="data.search" title="Users" flat :rows="stu.users" :columns="columns" row-key="id" :loading="stu.loading")
    template(#top-right)
      q-input(v-model="data.search" outlined dense type="text")
        template(#append)
          q-icon(name="search")
          q-icon.clickable(name="refresh" @click="stu.fetchUsers()")
    template(#body-cell-options="props")
      td
        v-btn(icon="mdi-pencil" variant="outlined" density="compact" color="primary" @click="editUser(props.row)")
  d-dialog(v-model="stu.show_add_dialog" @save="saveUser()")
    q-input(v-model="data.user.name" type="text" label="name")
    q-input(v-model="data.user.email" type="text" label="email")
    q-input(v-model="data.user.password" type="text" label="password")
    q-input(v-model="data.user.telephone" type="text" label="telephone")
    q-input(v-model="data.user.job" type="text" label="job")
</template>
