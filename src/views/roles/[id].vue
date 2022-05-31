<route lang="yaml">
meta:
  auth: true
  hide: true
 
</route>

<script setup lang="ts">

import axios from 'axios';
import { useUserStore } from '~/stores/user'
import routes from '~pages'
import VrTable from '../../components/VrTable.vue'
interface user {
    id: number,
    name: string,
}
interface perms {
    id: number,
    name?: string,
    permissions: Array<string>,
    users: Array<user>
}
const routes_have_roles = computed(() => routes.filter(r => r.meta && !r.meta.hide))
const show_add_role_dialog = ref(false)

const role = ref<perms>({ id: 0, users: [], permissions: [] })
const selected_users = ref<number[]>([])
const stu = useUserStore()
const route = useRoute()
const { t } = useI18n()

//const toast = useToast();

function editRole() {

    if (stu.users.length == 0) stu.fetchUsers()
    selected_users.value = role.value.users.map(u => u.id)
    show_add_role_dialog.value = true

}




onMounted(() => {
    axios.get('role/' + route.params.id).then(res => role.value = res.data)
})

function checkAll(r: any) {
    role.value.permissions = role.value.permissions.filter(p => p !== 'admin')
    if (!r.meta.roles) return

    if (role.value.permissions.some(s => s === r.path)) {//selected
        role.value.permissions = [...role.value.permissions, ...r.meta.roles.map(ro => r.path + '.' + ro)]
    }
    else {
        let removed = r.meta.roles.map(ro => r.path + '.' + ro)
        role.value.permissions = role.value.permissions.filter(s => !removed.includes(s))
    }

}

function setAdmin() {
    if (role.value.permissions.some(s => s === 'admin')) role.value.permissions = ['admin']
}

function updateRole() {
    axios.put('role/' + role.value.id, { name: role.value.name, permissions: role.value.permissions, role_users: selected_users.value }).then(res => {
        role.value = res.data
        show_add_role_dialog.value = false
    })
}
</script>

<template lang="pug">
d-page()
    template(#tools)
        q-btn(color="success" @click="editRole()" icon="edit" flat :label="t('button.edit')")
    VrTable(:data="role" :headers="['id', 'name', 'permissions', 'users']")

    d-dialog(v-model="show_add_role_dialog" @save="updateRole()")
        q-input(v-model="role.name" type="text" label="Role name" outlined variant="outlined")
        q-card
            q-card-section Role Users
                q-spacer
                q-btn(@click="stu.fetchUsers()") reload users   
            q-card-section
                q-checkbox(v-for="(user, i2) in stu.users" :key="i2" :label="user.name" :val="user.id" v-model="selected_users" hide-details)
        q-card
            q-card-section Role permissions
            q-card(outline color="primary")
                q-checkbox(val="admin" label="admin" v-model="role.permissions"  @click.stop="setAdmin()")
                    .text-red.text-center admin will have access to all permissions 
            q-card(outline flat v-for="route in routes_have_roles" :key="route.path")
                q-checkbox(size="lg" color="positive"  :label="t('pages.' + route.name)"   :val="route.path" v-model="role.permissions" multiple @click="checkAll(route)") 
                .flex.ml-3(v-show="role.permissions.includes(route.path)")
                    q-checkbox(v-for="perm in route.meta?.roles" hide-details :val="route.path + '.' + perm" multiple :label="t('button.' + perm)" v-model="role.permissions")

</template>
