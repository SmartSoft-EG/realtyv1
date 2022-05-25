// these APIs are auto-imported from @vueuse/core


export const isDark = useDark()
export const toggleDark = useToggle(isDark)

export const pick = (keys, obj) => keys.reduce((acc, key) => {
    acc[key] = obj[key];
    return acc;
}, {});




