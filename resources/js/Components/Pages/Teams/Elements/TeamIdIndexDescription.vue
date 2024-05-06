<template>
<div class="px-5">

  <div class="w-full bg-gray-900 text-white text-center text-2xl p-4 mb-4">
    DESCRIPTION
  </div>

    <p v-if="team.description" class="description mb-6 p-5">
      <!-- Display either the truncated or full description based on the state -->
      {{ showFullDescription ? team.description : truncatedDescription }}
      <!-- Ellipsis for truncated text -->
      <span v-if="!showFullDescription && needsTruncation">...</span>

      <br/><br/>

      <!-- Toggle button for showing full description -->
      <button v-if="needsTruncation && !showFullDescription"
              @click="toggleDescription"
              class="btn btn-wide justify-self-center">
        Read the full description
      </button>

<!--      <span v-if="showFullDescription">-->
<!--              {{ team.description }}-->
<!--            </span>-->
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
  return props.team.description.slice(0, 300)
})

const needsTruncation = computed(() => {
  return props.team.description.length > 300
})

const toggleDescription = () => {
  showFullDescription.value = !showFullDescription.value
}
</script>