/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/css/bootstrap.min.css'
import './styles/app.scss'

import 'bootstrap'
import Vue from 'vue'

import LinksList from './components/LinksList'
import Settings from './components/Settings'

new Vue({
  el: '#app',
  components: {
    LinksList,
    Settings,
  },
})