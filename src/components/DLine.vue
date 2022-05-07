<script setup lang="ts">
defineProps({
  title: String,
  type: String,
  body: String,
})

</script>

<template>
  <tr class="pa-2 ma-2" style="width:100%">
    <th class="t-title p-1 sm:p-2 w-sm  sm:w-md font-medium capitalize bg-light-300">
      {{ title }}
      <slot name="tools" />
    </th>
    <td class="t-data capitalize">
      <slot>{{ body }}</slot>
    </td>
  </tr>
</template>

<style scoped>
.t-title {
  font-size: 16px;
  color: rgb(19, 15, 76);
  border-bottom: #d6d5d5 solid 1px;
  border-right: solid 1px #d6d5d5
}

.t-data {
  padding: 8px;
  border-bottom: #d6d5d5 solid 1px;
  /* background-color: #f5f5f5; */
  font-size: 13px;

  width: 80%;
  /* min-width: 200px; */
}
</style>
