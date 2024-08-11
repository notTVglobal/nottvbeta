<template>
  <div class="relative group">
    <button @click.prevent="toggleTooltip"
            @mouseenter="showTooltip"
            @mouseleave="hideTooltip"
            :class="buttonClasses">
      <font-awesome-icon icon="fa-circle-down" class="mr-2"/>
      Save For Later
    </button>
    <div v-if="isTooltipVisible"
         class="absolute bottom-full mb-3 left-1/2 transform -translate-x-1/2 w-64 text-center flex flex-col items-center bg-pink-600 text-white text-sm rounded-lg px-5 py-3 shadow-xl z-10">
      <span class="animate-spin text-lg text-yellow-400">✨</span>
      <p class="mt-2">Exciting new features are on the way! This button will soon let you save content for later
        viewing. Stay tuned!</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isTooltipVisible = ref(false)

const toggleTooltip = () => {
  isTooltipVisible.value = !isTooltipVisible.value
}

const showTooltip = () => {
  isTooltipVisible.value = true
}

const hideTooltip = () => {
  isTooltipVisible.value = false
}

const buttonClasses = ref('flex bg-gray-600 text-white font-semibold px-4 py-2 rounded transition ease-in-out duration-150 items-center cursor-not-allowed')
</script>

<style scoped>
/* CSS specific to this button can be added here if needed */
</style>
