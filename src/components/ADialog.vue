<!-- BaseInput.vue component -->
<script setup lang="ts">
import type { FormInstance } from 'element-plus'

const props = defineProps({
    modelValue: Boolean,
    title: String,
    formData: Object,
    rules: Array
})
const formRef = ref(null)
const emit = defineEmits(['update:modelValue', 'save'])

const model = computed({
    get() {
        return props.modelValue
    },

    set(value) {
        return emit('update:modelValue', value)
    },
})


const save = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.validate((valid) => {
        if (valid) {
            emit('save')
        } else {
            console.log('error submit!')
            return false
        }
    })
}

</script>

<template>
    <el-dialog v-model="model" :title="title" custom-class="elc" width="1000">
        <el-form label-position="top" :model="formData" :rules="rules" @keyup.enter="save" ref="formRef">
            <slot></slot>
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="model = false">Cancel</el-button>
                <el-button type="primary" @click="save(formRef)">Confirm</el-button>
            </span>
        </template>
    </el-dialog>

</template>

<style>
.elc {
    max-width: 800px;
}
</style>
