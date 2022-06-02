
<script setup lang="ts">
import { useQuasar } from 'quasar';

const props = defineProps({
  add: String,
})
const q = useQuasar()
</script>
<template lang="pug">
div
  div(class="sticky top-10 z-20 flex items-center  align-center md:p-4 p-1 gap-1")
    q-btn(:icon="q.lang.rtl ? 'mdi-arrow-right' : 'mdi-arrow-left'" flat size="md" @click="$router.go(-1)" color="primary" class="px-2")
    q-btn(icon="mdi-reload" size="md" flat @click="$emit('refresh')" color="primary" class="px-2")
    slot(name="tools")
  div(class="md:p-6 md:ml-3 p-2")
    slot
</template>


