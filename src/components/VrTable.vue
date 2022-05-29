<template>

    <!-- <table>
        <tr v-for="(h, i) in _headers" :key="i">
            <th class="t-title p-1 sm:p-2 w-sm sm:w-30 font-medium capitalize bg-light-300 text-left">
                <div class="flex column center">
                    {{ t('inputs.' + h.text) }}
                    <slot :name="h.value + '-tools'"></slot>
                </div>


            </th>
            <td class="t-data capitalize">
                <slot :name="h.value">
                    {{ getValue(h.value) }}
                </slot>
            </td>
        </tr>
    </table> -->
    <div class="grid grid-cols-12 gap-2 sm:m-4">
        <div class="col-span-12 sm:col-span-6" v-for="(h, i) in _headers" :key="i">
            <div class="flex">

                <div class="t-title   p-1 max-w-40 sm:p-2 w-sm sm:w-35 font-medium capitalize  text-left">
                    <div class="flex items-center  center">

                        {{ t('inputs.' + h.text) }}
                        <slot :name="h.value + '-tools'"></slot>
                    </div>
                </div>

                <div class="flex-1 column center t-data capitalize">
                    <slot :name="h.value">
                        {{ getValue(h.value) }}
                    </slot>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">

const props = defineProps({
    //value: { type: Array },
    data: Object,
    headers: Array,
})
const { t } = useI18n()
const _headers = computed(() => {
    return props.headers.map((h) => {
        let items = h.split("|");
        let text = items[0];
        let value = items.length > 1 ? items[1] : items[0];
        return {
            text: text,
            //  align: this.$i18n.locale == "en" ? "left" : "right",
            value: value,
        };
    });
})






function getValue(s: string) {
    let o = props.data
    s = s.replace(/\[(\w+)\]/g, ".$1"); // convert indexes to properties
    s = s.replace(/^\./, ""); // strip a leading dot
    var a = s.split(".");

    for (var i = 0, n = a.length; i < n; ++i) {
        var k = a[i];
        if (k in o) {
            o = o[k];
        } else {
            return;
        }
    }
    return o;
}


</script>

<style lang="scss" scoped>
table {
    border-collapse: unset;
}

.t-title {
    font-size: 15px;
    color: rgb(19, 15, 76);
    // border-right: solid 1px #d6d5d5
}

.t-data {
    padding: 8px;
    border-bottom: #c6c6c6 solid 1px;
    /* background-color: #f5f5f5; */
    font-size: 14px;

    width: 80%;
    /* min-width: 200px; */
}
</style>
