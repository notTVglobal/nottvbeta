<template>
  <div class="px-5">
    <div v-if="!hideTitle" class="w-full bg-gray-900 text-white text-center tracking-wider text-2xl p-4 mb-4">
      DESCRIPTION
    </div>
    <div v-if="description" class="description mb-6 p-5">
      <!-- Use v-html to render the description with HTML formatting -->
<!--      <span v-html="showFullDescription ? description : truncatedDescription"></span>-->
      <tip-tap-description-render :description="showFullDescription ? description : truncatedDescription" />
      <span v-if="!showFullDescription && needsTruncation">...</span>
      <br><br>
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
});

const showFullDescription = ref(false);

const truncatedDescription = computed(() => {
  return props.description.length > 300 ? props.description.substring(0, 300) : props.description;
});

const needsTruncation = computed(() => {
  return props.description.length > 300;
});

const toggleDescription = () => {
  console.log('Toggling Description:', showFullDescription.value);
  showFullDescription.value = !showFullDescription.value;
};

</script>
