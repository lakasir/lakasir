window.Vue = require('vue');

import vuetify from './index.js'

import App from './App'

const app = new Vue({
    vuetify,
    render: h => h(App),
    el: '#app',
});
