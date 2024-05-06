<template>
  <div class="px-5">
    <div class="w-full bg-gray-900 text-white text-center tracking-wider text-2xl p-4 mb-4">
      DESCRIPTION
    </div>
    <p v-if="description" class="description mb-6 p-5">
      {{ showFullDescription ? description : truncatedDescription }}
      <span v-if="!showFullDescription && needsTruncation">...</span>
      <br><br>
      <button v-if="needsTruncation && !showFullDescription"
              @click="toggleDescription"
              class="btn btn-wide justify-self-center">
        Read the full description
      </button>
    </p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Props
const props = defineProps({
  description: String
});

const showFullDescription = ref(false);

const truncatedDescription = computed(() => {
  return props.description.length > 300 ? props.description.substring(0, 300) : props.description;
});

const needsTruncation = computed(() => {
  return props.description.length > 300;
});

const toggleDescription = () => {
  showFullDescription.value = !showFullDescription.value;
};
</script>

<style scoped>
/* Add any specific styles for this component here */
</style>
