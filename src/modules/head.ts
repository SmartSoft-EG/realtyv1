import { createHead } from '@vueuse/head'

// vueuse/head https://github.com/vueuse/head
export const install = (app: any) => {
  const head = createHead()
  app.use(head)
}
