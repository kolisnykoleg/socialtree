<template>
  <draggable
    v-model="links"
    tag="div"
    @end="dragEnd"
  >
    <template v-if="links.length">
      <div
        class="link"
        v-for="(link, index) of links"
      >
        <div class="d-inline-block">
          <input
            v-model="link.title"
            @input="changeLink(index)"
            type="text"
            name="title"
            placeholder="Title"
          >
          <input
            v-model="link.url"
            @input="changeLink(index)"
            type="text"
            name="url"
            placeholder="Url"
          >
        </div>
        <button
          class="btn btn-sm btn-danger"
          @click="deleteLink(index)"
        >
          Delete
        </button>
      </div>
    </template>
    <p v-else class="text-center">No links found</p>
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
input {
  background: inherit;
  color: inherit;
  border: none;
  outline: none;
}

.link {
  cursor: move;
}
</style>