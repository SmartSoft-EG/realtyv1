import { Quasar } from 'quasar'

// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/mdi-v6/mdi-v6.css'
import '@quasar/extras/bootstrap-icons/bootstrap-icons.css'
// Import Quasar css
import 'quasar/src/css/index.sass'
// Assumes your root component is App.vue
// and placed in same folder as main.js
//import langAr from 'quasar/lang/ar'


const config = {

  plugins: {}, // import Quasar plugins and add here
  config: {
    //lang: langAr,


    brand: {
      primary: '#64748b',
      show: '#64748b',
      negative: '#c70369'
      // ... or all other brand colors
    }
  }
  /*
 
    notify: {...}, // default set of options for Notify Quasar plugin
    loading: {...}, // default set of options for Loading Quasar plugin
    loadingBar: { ... }, // settings for LoadingBar Quasar plugin
    // ..and many more (check Installation card on each Quasar component/directive/plugin)
  }
  */
}

export const install = (app: any) => {
  app.use(Quasar, config)
}
