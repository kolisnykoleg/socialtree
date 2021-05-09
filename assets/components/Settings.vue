<template>
  <div class="row">
    <div class="col-6">
      <label for="style" class="form-label">Style</label>
      <textarea
        class="form-control"
        name="style"
        id="style"
        cols="30"
        rows="10"
        v-model="settings.style"
        @input="updateSettings"
      ></textarea>
    </div>
    <div class="col-6">
      <label for="icon_url" class="form-label">Icon url</label>
      <input
        class="form-control"
        type="text"
        name="icon_url"
        id="icon_url"
        v-model="settings.icon_url"
        @input="updateSettings"
      >
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: ['initSettings'],

  data () {
    return {
      settings: this.initSettings,
    }
  },

  methods: {
    updateSettings () {
      axios.post('/settings/update', this.settings)
    },
  },
}
</script>
