import { createI18n } from 'vue-i18n'

// Import i18n resources
// https://vitejs.dev/guide/features.html#glob-import
//
// Don't need this? Try vitesse-lite: https://github.com/antfu/vitesse-lite
const messages = Object.fromEntries(
  Object.entries(
    import.meta.globEager('../../locales/*.y(a)?ml'))
    .map(([key, value]) => {
      const yaml = key.endsWith('.yaml')
      return [key.slice(14, yaml ? -5 : -4), value.default]
    }),
)
function customRule(choice, choicesLength) {

  if (choice === 0) {
    return 0
  }

  if (choice == 1 || choice == 3) return 0
  else return 2

}

export const install = (app: any) => {
  const i18n = createI18n({
    fallbackWarn: false,
    missingWarn: false,
    legacy: false,
    locale: 'ar',
    pluralizationRules: {
      en: customRule
    },
    messages,
  })

  app.use(i18n)
}
