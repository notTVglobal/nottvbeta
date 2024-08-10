<template>
  <div class="relative">
    <template v-if="mode === 'icon'">
      <button class="" @click="copyToClipboard(text, id, 'text')">
        <font-awesome-icon icon="fa-clipboard" :class="buttonColor" class="ml-2 hover:cursor-pointer"/>
      </button>
    </template>
    <template v-else>
      <button :class="[buttonColor, buttonClass]" @click="copyToClipboard(text, id, 'text')">
        {{ buttonText }}
      </button>
    </template>
    <transition name="fade-slide-up">
      <div v-if="lastCopied.id === id && lastCopied.type === 'text'"
           class="copied-animation text-green-500 absolute"
           :class="labelPosition">
        Copied to clipboard!
      </div>
    </transition>
  </div>
</template>
<script setup>
import { useClipboard } from '@vueuse/core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { computed, ref } from 'vue'

const props = defineProps({
  text: String,
  id: {
    type: Number,
    default: 1
  },
  mode: {
    type: String,
    default: 'icon'
  },
  labelPosition: {
    // Top: Adjust this value to position it closer or further
    // Left: Center horizontally relative to the button
    type: String,
    default: '-top-10 left-0'
  },
  buttonText: {
    type: String,
    default: 'Copy'
  },
  buttonClass: {
    type: String,
    default: 'px-3 py-2 mb-2 mr-4 text-xs rounded-lg'
  },
  buttonColor: {
    type: String,
    default: 'blue'
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

// Computed property to generate the class names dynamically
const buttonColor = computed(() => {
  const baseClass = `text-${props.buttonColor}-500`;
  const hoverClass = `hover:text-${props.buttonColor}-700`;
  return `${baseClass} ${hoverClass}`;
});

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
  transform: translateX(-50%); /* Center it */
  white-space: nowrap; /* Keep the message on one line */
}
</style>