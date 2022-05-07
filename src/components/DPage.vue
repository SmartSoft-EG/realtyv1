<template>
  <div>
    <div class="sticky top-10 z-20 flex center bg-light-500 align-center md:p-4 p-1 gap-2">
      <v-btn icon="mdi-arrow-left" flat size="small" @click="$router.go(-1)" />
      <v-btn icon="mdi-reload" size="small" flat @click="$emit('refresh')" />

      <slot name="tools" />
    </div>
    <div class="md:p-6 p-2">
      <slot />
    </div>  </div>
</template>

<style>
</style>
