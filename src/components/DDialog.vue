<!-- BaseInput.vue component -->
<script setup lang="ts">
const props = defineProps({
  modelValue: Boolean,
  title: String,
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

</script>

<template lang="pug">
q-dialog(v-model="model" persistent)
  q-card(style="max-width: 500px;width:500px")
    q-toolbar
      q-toolbar-title
        span.text-weight-bold {{ title }}
      q-btn(v-close-popup flat round dense icon="close")
    q-card-section
      q-form.q-gutter-md(@submit.prevent='save')
        slot
        q-btn(label='Save', type='submit', color='primary')

</template>
