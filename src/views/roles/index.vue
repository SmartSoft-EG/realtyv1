<route lang="yaml">
name: roles
meta:
  auth: true
  group: admin
  roles: ['add','edit','delete','ignore','yes']
</route>

<script setup lang="ts">

import routes from '~pages'


import { useRoleStore } from '~/stores/role'
const { t } = useI18n()
const routes_have_roles = computed(() => routes.filter(r => r.meta && !r.meta.hide))
const cols = ['id', 'name', 'permissions', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: t('inputs.' + c),
        align: 'left',
    }
}))
const roles = computed(() => str.roles)
const str = useRoleStore()

const role = ref({
    id: 0,
    name: '',
    permissions: [],

})
onMounted(() => {
    str.fetchRoles()
})

function addRole() {
    role.value.id = 0
    role.value.name = ''
    role.value.permissions = []
    str.show_add_dialog = true
}

function editRole(r: any) {
    const t = (JSON.parse(JSON.stringify(r)))
    role.value.id = t.id
    role.value.name = t.name
    role.value.permissions = t.permissions
    str.show_add_dialog = true
}

function saveRole() {
    str.saveRole(role.value)
}

function checkAll(r: any) {
    console.log('t')
    role.value.permissions = role.value.permissions.filter(p => p != 'admin')

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
</script>

<template lang="pug">
d-page(@refresh="str.fetchRoles()")
    template(#tools)
        q-btn(color="primary" icon="add" flat @click="addRole()" :label="t('button.add') + ' ' + t('pages.roles', 2)")

    q-table(title="Roles" flat :rows="roles" :columns="columns" row-key="id")
        template(#body-cell-permissions="props")
            td
                span {{ props.row.permissions }}
        template(#body-cell-options="props")
            td
                q-btn(icon="mdi-eye" size="sm" class="mx-1" round color="info" :to="$route.path + '/' + props.row.id")

                q-btn(icon="mdi-pencil" size="sm" class="mx-1" round color="primary" @click.prevent.stop="editRole(props.row)")

    d-dialog(v-model="str.show_add_dialog" @save="saveRole()" :width="1200")
        q-input(v-model="role.name" outlined type="text" label="name" variant="outlined")
        q-card(outline color="primary")
            q-checkbox(val="admin" label="admin" v-model="role.permissions"  @click.stop="setAdmin()")
                .text-red.text-center admin will have access to all permissions 
        q-card(outline flat v-for="route in routes_have_roles" :key="route.path")
            q-checkbox(color="success"  :label="t('pages.' + route.name)"   :val="route.path" v-model="role.permissions" multiple @click="checkAll(route)") 
            .flex.ml-3(v-show="role.permissions.includes(route.path)")
                q-checkbox(v-for="perm in route.meta?.roles" hide-details :val="route.path + '.' + perm" multiple :label="t('button.' + perm)" v-model="role.permissions")

</template>
