<!-- BaseInput.vue component -->
<script setup lang="ts">
const props = defineProps({
    modelValue: Boolean,
    title: String,
    formData: Object,
    rules: Array
})
const frm = ref(null)
const emit = defineEmits(['update:modelValue', 'save'])

const model = computed({
    get() {
        return props.modelValue
    },

    set(value) {
        return emit('update:modelValue', value)
    },
})
function save() {
    frm.value.validate().then(() => emit('save'))
    //if (frm.value.validate()) emit('save')
}
const onFinishFailed = (errorInfo: any) => {
    //console.log('Failed:', errorInfo);
};
</script>

<template>
    <a-modal v-model:visible="model" :title="title" @ok="save">
        <a-form :rules="rules" @keyup.enter="save" ref="frm" style="width: 100%;" :model="formData" layout="vertical"
            name="basic" autocomplete="off" @submit="save" @finishFailed="onFinishFailed">
            <slot></slot>
        </a-form>
    </a-modal>

</template>
