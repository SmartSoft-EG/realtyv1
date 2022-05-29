
<route lang="yaml">
name: realty
meta: 
    auth: true
    group: realty_management
</route>

<script setup lang="ts">
import useValidation from '~/composables/validations'

import { useRealtyStore } from '~/stores/realty'
import type { Realty } from '~/composables'

import { serialize } from 'object-to-formdata';
const { t } = useI18n()
const { validate } = useValidation()
const cols = ['id', 'name', 'code', 'address', 'options']
const columns = computed(() => cols.map((c) => {
    return {
        name: c,
        field: c,
        label: c,
        align: 'left',
    }
}))
const str = useRealtyStore()

onMounted(() => {
    str.fetchRealty()
})
const realty = ref<Realty>({
    id: 0,
    name: '',
    address: '',
    code: '',
    description: '',
})
const search = ref('')
const imgs = ref([])

function addRealty() {
    realty.value = {
        id: 0,
        name: '',
        address: '',
        code: '',
        description: '',
    }
    str.show_add_dialog = true
}


function editRealty(rt: Realty) {
    realty.value = rt
    str.show_add_dialog = true
}

function saveRealty() {
    const data = serialize(
        {
            id: realty.value.id,
            name: realty.value.name,
            description: realty.value.description,
            code: realty.value.code,
            address: realty.value.address,
            imgs: imgs.value
        }
    )
    str.saveRealty(data)
}

function deleteRealty(id: number) {
    str.deleteRealty(id)
}



</script>

<template>
    <d-page @refresh="str.fetch()"><template #tools>
            <q-btn color="primary" @click="addRealty" icon="mdi-plus"
                :label="t('button.add') + ' ' + t('general.realty', 2)"></q-btn>
        </template>
        <q-table title="Realty" :filter="search" flat :rows="str.realty_list" :columns="columns" row-key="id"><template
                #top-right>
                <q-input v-model="search" outlined dense type="text"><template #append>
                        <q-icon name="search"></q-icon>
                    </template></q-input>
            </template><template #body-cell-options="props">
                <td class="flex gap-2 items-center">
                    <q-btn icon="mdi-eye" size="sm" round color="positive" :to="'/realty/' + props.row.id"></q-btn>
                    <q-btn icon="mdi-pencil" size="sm" round color="info" @click="editRealty(props.row)"></q-btn>
                    <q-btn icon="mdi-close" size="sm" round color="red" @click="deleteRealty(props.row.id)"></q-btn>
                </td>
            </template></q-table>
        <!-- <div class="b-1 p-2 bg-cool-gray-200">
            <h5 class="text-center"> عقار رقم 12 </h5>

            الدور الاول
            <div class="flex ">
                <div class="b-2 m-1 p-1 flex-1 h-12 bg-green-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-blue-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-green-200"></div>
            </div>
            <hr>
            الدور الثالث
            <div class="flex">
                <div class="b-2 m-1 p-1 flex-1 h-12 bg-red-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-blue-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-red-200"></div>
            </div>
            الدور الثاني
            <div class="flex ">
                <div class="b-2 m-1 p-1 flex-1 h-12 bg-green-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-blue-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-green-200"></div>
            </div>
            <hr>
            الدور الاول
            <div class="flex">
                <div class="b-2 m-1 p-1 flex-1 h-12 bg-red-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-green-200"></div>
                <div class="b-2 m-1 p-1  flex-1 h-12 bg-green-200"></div>
            </div>
            <hr>
            الدور الارضي
            <div class="flex">
                <div class="b-2 p-1 flex-1 h-12 bg-red-200"> محل</div>
                <div class="b-2  p-1  flex-1 h-12 bg-green-200"> محل </div>
                <div class="b-2  p-1  flex-1 h-12 bg-green-200">محل</div>
                <div class="b-2  p-1  flex-1 h-12 bg-green-200">محل</div>
                <div class="b-2 p-1  flex-1 h-12 bg-green-200">محل</div>

            </div>
        </div> -->


        <d-dialog v-model="str.show_add_dialog" :title="t('button.add') + ' ' + t('general.realty', 2)"
            @save="saveRealty()">
            <q-input v-model="realty.name" type="text" :label="t('inputs.name')"
                :rules="[v => validate('required|min:5|max:20', v)]"></q-input>
            <q-input v-model="realty.code" type="text" :label="t('inputs.code')"
                :rules="[v => validate('required', v)]"></q-input>
            <q-input v-model="realty.size" type="number" :label="t('inputs.size')"
                :rules="[v => validate('required', v)]"></q-input>
            <q-input v-model="realty.floors_count" type="number" :label="t('inputs.floors_count')"
                :rules="[v => validate('required', v)]"></q-input>
            <q-input v-model="realty.address" type="text" :label="t('inputs.address')"
                :rules="[v => validate('required', v)]"></q-input>
            <q-input v-model="realty.description" type="textarea" :label="t('inputs.description')"></q-input>
            <q-file v-model="imgs" multiple :label="t('inputs.docs')"></q-file>
        </d-dialog>
    </d-page>
</template>
