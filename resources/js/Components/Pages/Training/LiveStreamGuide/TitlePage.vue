<template>
  <div class="title-page">
    <div class="title-content">
      <div class="logo-container"><ApplicationLogo class="max-w-48"/>

      </div>
      <h1>Welcome to the notTV Live Streaming Guide!</h1>
      <p>Embark on your journey to becoming a streaming expert with our interactive guide.</p>
      <button @click="startJourney">Begin Adventure</button>
    </div>
  </div>
</template>

<script setup>
import { defineEmits, onMounted } from 'vue'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'

// Emitting an event to navigate from the title page to the main content
const emit = defineEmits(['navigate']);

function startJourney() {
  handleNavigation();
}

function handleNavigation() {
  if (!window.location.hash) {
    // Set default hash if none is present
    window.location.hash = 'intro';
  }
  // Emit navigate with the current hash (new or existing)
  emit('navigate', window.location.hash.slice(1));
}

// Execute navigation directly if the page is loaded with a hash
onMounted(() => {
  if (window.location.hash) {
    handleNavigation();
  }
});
</script>

<style scoped>
.title-page {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  width: 100vw;
  background: linear-gradient(to right, #FFD700, #6495ED); /* Bright and engaging background */
  color: #2F4F4F; /* Deep, storybook font color */
  text-align: center;
  font-family: 'Comic Neue', 'Comic Sans MS', Arial, sans-serif; /* Playful, child-friendly font */
}

.title-content {
  padding: 20px;
}

.logo-container {
  display: flex;
  justify-content: center;
  width: 100%;
  margin-bottom: 2rem;
}

h1 {
  font-size: 3rem; /* Large size for emphasis */
  margin-bottom: 20px;
}

p {
  font-size: 1.5rem;
  margin-bottom: 20px;
}

button {
  font-size: 1.25rem;
  padding: 10px 20px;
  background-color: #FF6347; /* Tomato color button */
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #FF4500; /* Darker tomato on hover */
}
</style>
