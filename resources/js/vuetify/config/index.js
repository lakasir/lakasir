import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
  icons: {
    iconfont: 'mdiSvg', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
  },
  breakpoint: {
    thresholds: {
      xs: 340,
      sm: 540,
      md: 540,
      lg: 540,
    },
    scrollBarWidth: 24,
  },
}

export default new Vuetify(opts)
