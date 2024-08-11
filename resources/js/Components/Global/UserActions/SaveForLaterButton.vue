<template>
  <div class="relative group">
    <button @click.prevent="toggleTooltip"
            @mouseenter="showTooltip"
            @mouseleave="startFadeOutDelay"
            :class="buttonClasses">
      <font-awesome-icon icon="fa-circle-down" class="mr-2"/>
      Save For Later
    </button>
    <transition name="fade">
      <div v-if="isTooltipVisible || isFadingOut"
           class="absolute bottom-full mb-3 left-1/2 transform -translate-x-1/2 w-64 text-center flex flex-col items-center bg-pink-600 text-white text-sm rounded-lg px-5 py-3 shadow-xl z-10">
        <span class="animate-spin text-lg text-yellow-400">✨</span>
        <p class="mt-2">Exciting new features are on the way! This button will soon let you save content for later
          viewing. Stay tuned!</p>
      </div>
    </transition>
  </div>
</template>


<script setup>
import { ref } from 'vue'

const isTooltipVisible = ref(false)
const isFadingOut = ref(false)
let fadeOutTimeout = null

const toggleTooltip = () => {
  if (!isTooltipVisible.value) {
    showTooltip()
  } else {
    hideTooltip()
  }
}

const showTooltip = () => {
  clearTimeout(fadeOutTimeout)
  isTooltipVisible.value = true
  isFadingOut.value = false
}

const hideTooltip = () => {
  fadeOutTimeout = setTimeout(() => {
    isTooltipVisible.value = false
    isFadingOut.value = false
  }, 500) // Fade out after 1 second delay
}

const startFadeOutDelay = () => {
  isFadingOut.value = true
  hideTooltip()
}

const buttonClasses = ref('flex bg-gray-600 text-white font-semibold px-4 py-2 rounded transition ease-in-out duration-150 items-center cursor-not-allowed')
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 1s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
