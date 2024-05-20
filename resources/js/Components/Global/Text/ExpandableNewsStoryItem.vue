<template>
    <div v-if="content" :class="props.class">
      <!-- Use v-html to render the description with HTML formatting -->
      <!--      <span v-html="showFullDescription ? description : truncatedDescription"></span>-->
      <!-- Use TipTapDescriptionRender component to render HTML content -->
      <tip-tap-render :content="displayedContent"/>
      <div v-if="!hideButton" class="flex w-full justify-center">
        <button v-if="needsTruncation && !showFullContent"
                @click="toggleContent"
                class="btn btn-wide justify-self-center">
          Read more
        </button>
      </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import TipTapRender from '@/Components/Global/TextEditor/TipTapRender.vue'

// Props
const props = defineProps({
  content: String,
  class: String,
  hideButton: false,
})

const showFullContent = ref(false)

const truncatedContent = computed(() => {
  if (props.content.length > 210) {
    return props.content.substring(0, 210) + '...'  // Add ellipsis directly here
  }
  return props.content
})

const needsTruncation = computed(() => {
  return props.content.length > 210
})

const toggleContent = () => {
  console.log(props.content)
  showFullContent.value = !showFullContent.value
}

// Ensure rendering triggers
const displayedContent = computed(() => {
  return showFullContent.value ? props.content : truncatedContent.value
})

</script>
