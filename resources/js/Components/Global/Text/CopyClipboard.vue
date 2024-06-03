<template>
  <div class="relative">
    <template v-if="mode === 'icon'">
      <button class="" @click="copyToClipboard(text, id, 'text')">
        <font-awesome-icon icon="fa-clipboard" class="ml-2 text-blue-500 hover:text-blue-700 hover:cursor-pointer"/>
      </button>
    </template>
    <template v-else>
      <button :class="buttonClass" @click="copyToClipboard(text, id, 'text')">
        {{ buttonText }}
      </button>
    </template>
    <transition name="fade-slide-up">
      <div v-if="lastCopied.id === id && lastCopied.type === 'text'"
           class="copied-animation text-green-500 absolute">
        Copied to clipboard!
      </div>
    </transition>
  </div>
</template>
<script setup>
import { useClipboard } from '@vueuse/core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { ref } from 'vue'

const props = defineProps({
  text: String,
  id: Number,
  mode: {
    type: String,
    default: 'icon'
  },
  buttonText: {
    type: String,
    default: 'Copy'
  },
  buttonClass: {
    type: String,
    default: 'px-3 py-2 mb-2 mr-4 text-xs text-white bg-blue-600 hover:bg-blue-500 rounded-lg'
  }
})

// Dynamically set source for the clipboard
const sourceToCopy = ref('')
const {copy, copied} = useClipboard()
const lastCopied = ref({id: null, type: null})

function copyToClipboard(text, id, type) {
  sourceToCopy.value = text
  copy(text)
  lastCopied.value = {id, type}

  setTimeout(() => {
    lastCopied.value = {id: null, type: null} // Reset after the animation
  }, 3000) // Ensure this matches your animation duration
}

</script>
<style scoped>
@keyframes fadeSlideUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  10% {
    opacity: 1;
    transform: translateY(0);
  }
  90% {
    opacity: 1;
    transform: translateY(0);
  }
  100% {
    opacity: 0;
    transform: translateY(-20px);
  }
}

.copied-animation {
  animation: fadeSlideUp 3s ease-in-out forwards;
  position: absolute;
  z-index: 10; /* Ensure it's above other content */
  background: rgba(255, 255, 255, 1); /* Semi-transparent white background */
  border: 1px solid #ccc; /* Optional: adds a subtle border */
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2); /* Optional: adds a slight shadow for depth */
  padding: 4px 8px;
  border-radius: 4px; /* Soften the edges */
  top: -30px; /* Adjust this value to position it closer or further */
  left: 0; /* Center horizontally relative to the button */
  transform: translateX(-50%); /* Center it */
  white-space: nowrap; /* Keep the message on one line */
}
</style>