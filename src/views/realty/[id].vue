
<route lang="yaml">
meta: 
    auth: true
    hide: true
</route>

<script setup lang="ts">

import type { Realty } from '~/composables/interfaces'
import axios from 'axios';
import { serialize } from 'object-to-formdata';
const route = useRoute()
const { t } = useI18n()
const realty = ref<Realty>({
    id: 0,
    name: '',
    code: '',
    unites: [],
})

const imgs = ref([])

const show_add_dialog = ref(false)
onMounted(() => {
    axios.get('realty/' + route.params.id).then(res => realty.value = res.data)
})


const docs = computed(() => realty.value.docs.map(d => getCurrentInstance()?.proxy.$storage + d.path))
function editRealty() {

    show_add_dialog.value = true
}

function updateRealty() {
    const data = serialize({
        _method: 'put',
        id: realty.value.id,
        name: realty.value.name,
        description: realty.value.description,
        code: realty.value.code,
        imgs: imgs.value
    })

    axios.post('realty/' + realty.value.id, data).then(res => {
        realty.value = res.data
        show_add_dialog.value = false
    })
}

function addRealtyUnit() {

}

</script>

<template lang="pug">
d-page(@refresh="str.fetchRealty()")
    template(#tools)
        q-btn(color="info" icon="edit" @click="editRealty" :label="t('button.edit')")
        q-btn(color="primary" @click="addRealtyUnit" icon="mdi-plus" :label="t('button.add') + ' ' + t('pages.realty_units', 2)")

    vr-table(:data="realty" :headers="['id', 'name', 'code', 'address', 'floors_count', 'size', 'description', 'docs']")
        template(#docs)
            .flex.center.justify-center.gap-2
                el-image(style="height: 100px" v-for="(doc, i) in realty.docs" :key="i" :src="$storage + doc.path" :preview-src-list="docs" :initial-index="4" fit="cover")
    d-line(title="unites")
        | {{ realty.unites }}
    d-dialog(v-model="show_add_dialog" title="Add realty" @save="updateRealty()")
        q-input(v-model="realty.name" type="text" label="name")
        q-input(v-model="realty.code" type="text" label="code")
        q-input(v-model="realty.address" type="text" label="address")
        q-input(v-model="realty.description" type="textarea" label="description")
        q-file(v-model="imgs" multiple label="files")
</template>
