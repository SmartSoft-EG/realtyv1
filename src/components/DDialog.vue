<!-- BaseInput.vue component -->
<script setup lang="ts">
import { useQuasar } from 'quasar';

const props = defineProps({
  modelValue: Boolean,
  title: String,
})
const q = useQuasar()
const { t } = useI18n()
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
  q-card(style="max-width: 500px;width:500px" :dir="q.lang.rtl ? 'rtl' : 'ltr'")
    q-toolbar(class="bg-blue-gray-300")
      q-toolbar-title
        span.text-weight-bold {{ title }}
      q-btn(v-close-popup flat round color="negative" dense icon="close")
    q-card-section
      q-form.q-gutter-md(@submit.prevent='save')
        slot
        .flex.items-center.justify-center.gap-2
          q-btn(:label="t('button.save')", type='submit', color='primary' icon="check")

</template>
