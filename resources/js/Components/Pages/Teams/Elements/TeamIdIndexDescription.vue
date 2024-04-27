<template>
<div>
    <p v-if="team.description" class="description mb-6 p-5">
      {{ truncatedDescription }}
      <span v-if="team.description.length > 300 && !showFullDescription">
              ...
            </span>
      <br/><br/>
      <button v-if="team.description.length > 300 && !showFullDescription"
              @click="showFullDescription = true"
              class="btn btn-wide justify-self-center">Read the full description
      </button>
      <span v-if="showFullDescription">
              {{ team.description }}
            </span>
    </p>
  </div>
</template>
<script setup>
import { computed, ref } from 'vue'

let props = defineProps({
  team: Object,
})

const showFullDescription = ref(false)

const truncatedDescription = computed(() => {
  if (props.team.description.length <= 300 || showFullDescription) {
    return props.team.description
  } else {
    return props.team.description.slice(0, 300)
  }
})
</script>