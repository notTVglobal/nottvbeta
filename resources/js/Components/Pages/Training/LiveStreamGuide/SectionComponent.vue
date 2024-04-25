<template>
  <div class="pb-64">
    <div id="topDiv"></div>
    <div class="section-header flex flex-row items-center gap-6">
      <img src="/storage/images/Ping.png" alt="notTV Ping" class="max-h-20"/>
      <font-awesome-icon :icon="`fa-${section.number}`" class="text-3xl"/>

      <!-- Static title/header for all sections -->
      <h1>{{ section.name }}</h1>
      <p>{{ section.description }}</p>

      <button v-if="props.currentIndex < props.totalSections - 1"
              @click="navigateToNext"
              class="next-button">
        Next</button>
      <!-- Dynamically load the component based on section.type -->
    </div>
    <div class="section">
      <component :is="dynamicComponent"/>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, shallowRef, watch } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const props = defineProps({
  section: Object,
  navigateToNext: Function,  // Accept the navigateToNext function as a prop
  currentIndex: Number, // The index of the current section
  totalSections: Number // The total number of sections
});

const dynamicComponent = shallowRef(null);

const loadComponent = (sectionId) => {
  const imports = {
    'intro': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionIntroduction.vue'),
    'roles': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionRoles.vue'),
    'technical': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionTechnicalSetup.vue'),
    'management': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionManagement.vue'),
    'promotion': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionPromotion.vue'),
    'support': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionSupport.vue'),
    'interaction': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionInteractionTechniques.vue'),
    'graphics': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionGraphics.vue'),
    'event': () => import('@/Components/Pages/Training/LiveStreamGuide/Sections/SectionEventFlow.vue')
  };

  if (imports[sectionId]) {
    imports[sectionId]().then(comp => {
      dynamicComponent.value = comp.default;
    }).catch(error => {
      console.error('Failed to load the component:', error);
      dynamicComponent.value = null;
    });
  } else {
    console.warn('No component found for ID:', sectionId);
    dynamicComponent.value = null;
  }
};

// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById("topDiv");
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({ behavior: 'smooth' });
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  });
};

// Watch for changes in section.id
watch(() => props.section.id, (newId, oldId) => {
  if (newId !== oldId) {
    loadComponent(newId);
    scrollToTop();
  }
}, { immediate: true });

onMounted(() => {
  scrollToTop(); // Optionally scroll to top when the component mounts
});
</script>

<style scoped>
.section-header {
  padding: 20px;
  border: 2px solid #000; /* Dark outline for readability */
  margin: 20px;
  background-color: #98FB98; /* Pale green for a soft, inviting look */
}
.next-button {
  padding: 10px 20px;
  background-color: #f0f0f0; /* Gray background */
  color: black;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px; /* Space from the content above */
  margin-left: auto; /* Pushes the button to the right */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow in normal state */
  transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transition for hover effect */

}

.next-button:hover {
  background-color: #e0e0e0; /* Slightly darker gray on hover */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Slightly more pronounced shadow on hover */
}
</style>
