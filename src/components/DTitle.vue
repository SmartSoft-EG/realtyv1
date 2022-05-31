<script setup lang="ts">
const { t } = useI18n()
const props = defineProps({
    title: String
})
</script>
<template>
    <div>


        <h4 class="my-4">
            <q-icon class="mx-2" name="circle" color="info">{{ t('general.' + title) }}</q-icon>
        </h4>
    </div>
</template>
