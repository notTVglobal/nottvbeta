<template>
  <div class="" :class="props.class">
    <div v-if="!hideTitle" class="w-full bg-gray-900 text-white text-center tracking-wider text-2xl mb-4 p-4">
      DESCRIPTION
    </div>
    <div v-if="description" class="description mb-6">
      <!-- Use v-html to render the description with HTML formatting -->
<!--      <span v-html="showFullDescription ? description : truncatedDescription"></span>-->
      <!-- Use TipTapDescriptionRender component to render HTML content -->
      <tip-tap-description-render :description="displayedDescription" :key="showFullDescription"/>
      <button v-if="needsTruncation && !showFullDescription"
              @click="toggleDescription"
              class="btn btn-wide justify-self-center">
        Read the full description
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import TipTapDescriptionRender from '@/Components/Global/TextEditor/TipTapDescriptionRender.vue'

// Props
const props = defineProps({
  description: String,
  hideTitle: Boolean,
  class: String,
});

const showFullDescription = ref(false);

const truncatedDescription = computed(() => {
  if (props.description.length > 300) {
    return props.description.substring(0, 300) + '...';  // Add ellipsis directly here
  }
  return props.description;
});

const needsTruncation = computed(() => {
  return props.description.length > 300;
});

const toggleDescription = () => {
  console.log('Toggling Description:', showFullDescription.value);
  console.log(props.description)
  showFullDescription.value = !showFullDescription.value;
};

// Ensure rendering triggers
const displayedDescription = computed(() => {
  return showFullDescription.value ? props.description : truncatedDescription.value;
});

</script>
