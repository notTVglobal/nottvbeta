<template>
  <div v-if="showPopup" class="popup">
    <div class="popup-content">
      <span class="close" @click="closePopup">&times;</span>
      <h2>Add notTV to Your Home Screen</h2>
      <p class="mt-2">
        Experience notTV like never before by adding it to your home screen. This allows you to quickly access our
        platform, bypass censorship, and enjoy a seamless user experience.
      </p>
      <p>
        Follow these simple steps to add notTV to your home screen:
      </p>
      <ol class="text-black mt-2">
        <li>
          <strong>Open your browser's menu</strong> <br>
          <img src="path/to/menu-icon.png" alt="Browser menu icon" class="instruction-image">
        </li>
        <li>
          <strong>Tap "Add to Home Screen"</strong> <br>
          <img src="path/to/add-icon.png" alt="Add to Home Screen icon" class="instruction-image">
        </li>
        <li>
          <strong>Follow the prompts to add notTV</strong> <br>
          <img src="path/to/prompt-icon.png" alt="Follow prompts icon" class="instruction-image">
        </li>
      </ol>
      <p>
        By adding notTV to your home screen, you ensure you have direct access to our content, free from restrictions
        and enhanced for your convenience.
      </p>
      <button @click="closePopup" class="btn-close">Got it!</button>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  showPopup: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits(['close'])

const closePopup = () => {
  emit('close')
}
</script>
<style scoped>
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8); /* Darker background for emphasis */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.popup-content {
  background-color: #ffffff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
  text-align: left;
  max-width: 500px;
  width: 100%;
  max-height: 80vh; /* Ensure the popup height does not exceed the viewport height */
  overflow-y: auto; /* Make content scrollable if it exceeds the popup height */
  position: relative;
}

.popup .close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 25px;
  cursor: pointer;
  color: #888888;
  transition: color 0.2s;
}

.popup .close:hover {
  color: #333333;
}

.popup h2 {
  margin-top: 0;
  font-size: 1.8em;
  color: #333333;
}

.popup p {
  font-size: 1em;
  color: #333333;
}

.popup ol {
  padding-left: 20px;
}

.popup ol li {
  margin-bottom: 15px;
}

.instruction-image {
  width: 100px; /* Adjust size as needed */
  height: auto;
  display: block;
  margin: 10px 0;
}

.btn-close {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #007bff;
  color: #ffffff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1em;
  transition: background-color 0.3s;
}

.btn-close:hover {
  background-color: #0056b3;
}

/* Dark mode styling */
@media (prefers-color-scheme: dark) {
  .popup-content {
    background-color: #333333;
    color: #ffffff;
  }

  .popup h2,
  .popup p {
    color: #ffffff;
  }

  .popup .close {
    color: #bbbbbb;
  }

  .popup .close:hover {
    color: #ffffff;
  }

  .btn-close {
    background-color: #0056b3;
  }

  .btn-close:hover {
    background-color: #003d7a;
  }
}
</style>
