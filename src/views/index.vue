<script setup lang="ts">
import * as dayjs from 'dayjs'
onMounted(() => {
})

</script>
<template>

    {{ dayjs().format('dd-mm-yyyy') }}
    <login-screen></login-screen>
</template>