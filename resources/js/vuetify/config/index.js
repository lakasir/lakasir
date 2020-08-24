import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VueI18n from 'vue-i18n'
import en from './../locale/en';
import id from './../locale/id';

Vue.use(Vuetify)
Vue.use(VueI18n)

const messages = {
  en: {
    $vuetify: en,
  },
  id: {
    $vuetify: id,
  },
}

// Create VueI18n instance with options
const i18n = new VueI18n({
  locale: 'en', // set locale
  messages, // set locale messages
})

const opts = {
  icons: {
    iconfont: 'mdiSvg', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
  },
  lang: {
    t: (key, ...params) => i18n.t(key, params)
  }
}

export default new Vuetify(opts)
