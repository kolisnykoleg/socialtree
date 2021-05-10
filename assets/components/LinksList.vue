<template>
  <draggable
    v-model="links"
    tag="ul"
    class="list-group"
    @end="dragEnd"
  >
    <template v-if="links.length">
      <li
        class="list-group-item py-3"
        v-for="(link, index) of links"
      >
        <input
          v-model="link.title"
          @input="changeLink(index)"
          type="text"
          name="title"
          class="form-control mb-2"
          placeholder="Title"
        >
        <input
          v-model="link.url"
          @input="changeLink(index)"
          type="text"
          name="url"
          class="form-control mb-2"
          placeholder="Url"
        >
        <button
          class="btn btn-sm btn-danger d-block ms-auto"
          @click="deleteLink(index)"
        >
          Delete
        </button>
      </li>
    </template>
  </draggable>
</template>

<script>
import draggable from 'vuedraggable'
import axios from 'axios'

export default {
  components: {
    draggable,
  },

  props: ['initLinks'],

  data () {
    return {
      links: this.initLinks,
      dragging: false,
    }
  },

  methods: {
    dragEnd () {
      this.setPosition()
      this.links.forEach(this.updateLink)
    },

    setPosition () {
      this.links.forEach((link, index) => link.position = index)
    },

    updateLink (link) {
      axios.post(`/link/${link.id}/update`, link)
    },

    deleteLink (index) {
      const link = this.links[index]
      axios.post(`/link/${link.id}`)
        .then(() => {
          this.links.splice(index, 1)
        })
    },

    changeLink (index) {
      this.updateLink(this.links[index])
    },
  },
}
</script>

<style scoped>
.list-group-item {
  cursor: move;
}
</style>