
<script setup lang="ts">
import { useQuasar } from 'quasar';

const props = defineProps({
  add: String,
})
const q = useQuasar()
</script>
<template>
  <div>
    {{ }}
    <div class="sticky top-10 z-20 flex center bg-light-500 align-center md:p-4 p-1 gap-2">
      <q-btn :icon="q.lang.rtl ? 'mdi-arrow-right' : 'mdi-arrow-left'" flat size="small" @click="$router.go(-1)" />
      <q-btn icon="mdi-reload" size="small" flat @click="$emit('refresh')" />
      <slot name="tools" />
    </div>
    <div class="md:p-6 p-2">
      <slot>
      </slot>
    </div>
  </div>
</template>


