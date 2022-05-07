<template>
    <table>
        <tr v-for="(h, i) in _headers" :key="i">
            <th class="t-title p-1 sm:p-2 w-sm  sm:w-md font-medium capitalize bg-light-300">
                <div class="flex column center">
                    {{ h.text }}
                    <slot :name="h.value + '-tools'"></slot>
                </div>


            </th>
            <td class="t-data capitalize">
                <slot :name="h.value">
                    {{ getValue(h.value) }}
                </slot>
            </td>
        </tr>
    </table>
</template>

<script setup lang="ts">

const props = defineProps({
    //value: { type: Array },
    data: Object,
    headers: Array,
})

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
