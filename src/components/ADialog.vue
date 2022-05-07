<!-- BaseInput.vue component -->
<script setup lang="ts">
const props = defineProps({
    modelValue: Boolean,
    title: String,
    form_data: Object
})

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
    emit('save')
}
const onFinishFailed = (errorInfo: any) => {
    console.log('Failed:', errorInfo);
};
</script>

<template>
    <a-modal v-model:visible="model" :title="title" :cancel-button-props="{ disabled: true }">
        <a-form style="width: 100%;" :model="form_data" name="basic" :label-col="{ span: 8 }" autocomplete="off"
            @finish="save" @finishFailed="onFinishFailed">
            <slot></slot>
        </a-form>
    </a-modal>

</template>
