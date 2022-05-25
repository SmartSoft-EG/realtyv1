import { useI18n } from "vue-i18n"

export default function useValidation() {
    const { t } = useI18n()

    const required = (value: any) => !value ? t('validation.required') : true
    const max = (value: any, length: number) => (value && value.length > length) ? t('validation.max', { length: length }) : true
    const min = (value: any, length: number) => (value && value.length < length) ? t('validation.min', { length: length }) : true
    const email = (value: any) => {
        let reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
        let valid = reg.test(value)
        if (!valid) return t('validation.email')
        return true
    }

    const validate = (rules: string, value: any, length: number = 0) => {
        let data = {}
        let ar = rules.split('|')
        ar.forEach(st => {
            if (st.includes(':')) {
                st = st.split(':')
                data[st[0]] = st[1]
            } else {
                data[st] = st
            }
        })

        if (data['required'] && !value) return t('validation.required');
        if (data['min'] && value.length < data['min']) return t('validation.min', { length: data['min'] })
        if (data['max'] && value.length > data['max']) return t('validation.max', { length: data['max'] })
        if (data['email']) {
            let reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
            let valid = reg.test(value)
            if (!valid) return t('validation.email')
        }
        return true
    }

    return {
        required, email, min, max, validate
    };

}


