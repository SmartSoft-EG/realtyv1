import 'vuetify/styles' // Global CSS has to be imported
import { createVuetify } from 'vuetify' // Replaces new Vuetify(...)

// Assumes your root component is App.vue
// and placed in same folder as main.js
import '@mdi/font/css/materialdesignicons.css'

import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({ components, directives })

export const install = (app: any) => {
    app.use(vuetify)
}
