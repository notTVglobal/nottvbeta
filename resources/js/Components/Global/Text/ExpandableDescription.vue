<template>
  <div class="" :class="props.class">
    <div v-if="!hideTitle" class="w-full bg-gray-900 text-white text-center tracking-wider text-2xl mb-4 p-4">
      DESCRIPTION
    </div>
    <div v-if="description" class="description mb-1">
      <!-- Use v-html to render the description with HTML formatting -->
      <!--      <span v-html="showFullDescription ? description : truncatedDescription"></span>-->
      <!-- Use TipTapDescriptionRender component to render HTML content -->
      <tip-tap-render :content="displayedDescription" :key="showFullDescription"/>
      <div class="flex w-full justify-center">
        <button v-if="needsTruncation && !showFullDescription"
                @click="toggleDescription"
                class="btn btn-wide justify-self-center bg-gray-200 hover:bg-gray-400 transition text-gray-800">
          Read the full description
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import TipTapRender from '@/Components/Global/TextEditor/TipTapRender.vue'

// Props
const props = defineProps({
  description: String,
  hideTitle: Boolean,
  class: String,
  length: {
    type: Number,
    default: 210
  }
})

const showFullDescription = ref(false)

const truncatedDescription = computed(() => {
  if (props.length === 0 || props.description.length <= props.length) {
    return props.description
  }
  return props.description.substring(0, props.length) + '...'
})

const needsTruncation = computed(() => {
  return props.length !== 0 && props.description.length > props.length
})


const toggleDescription = () => {
  showFullDescription.value = !showFullDescription.value
}

// Ensure rendering triggers
const displayedDescription = computed(() => {
  return showFullDescription.value ? props.description : truncatedDescription.value
})

</script>
