<template>
  <div class="main-container">
    <NavigationBar :sections="sections" :activeSection="currentHash" @navigate="handleNavigation"/>
    <div class="content" v-if="currentSection">
      <SectionComponent :section="currentSection"
                        :navigateToPrev="navigateToPrevSection"
                        :navigateToNext="navigateToNextSection"
                        :currentIndex="currentIndex"
                        :totalSections="sections.length"/>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import NavigationBar from '@/Components/Pages/Training/LiveStreamGuide/NavigationBar.vue'
import SectionComponent from '@/Components/Pages/Training/LiveStreamGuide/SectionComponent.vue'

const sections = [
  { name: 'Introduction', id: 'intro', number: 1 },
  { name: 'Roles and Accountabilities', id: 'roles', number: 2 },
  { name: 'Technical Setup', id: 'technical', number: 3 },
  { name: 'notTV Page Setup and Management', id: 'management', number: 4 },
  { name: 'Promotion and Engagement', id: 'promotion', number: 5 },
  { name: 'Troubleshooting and Support', id: 'support', number: 6 },
  { name: 'Viewer Interaction Techniques', id: 'interaction', number: 7 },
  { name: 'Dynamic Graphics and Real-Time Information', id: 'graphics', number: 8 },
  { name: 'Event Flow Management', id: 'event', number: 9 }
];

// Calculate the current index
const currentIndex = computed(() => sections.findIndex(s => s.id === currentSectionId.value));

// Watch the window.location directly and react to changes
const currentSectionId = ref(window.location.hash.replace('#', '') || 'intro');
const currentSection = ref(findSection(currentSectionId.value));

// Helper function to find a section by ID
function findSection(id) {
  return sections.find(s => s.id === id) || sections[0]; // Default to first section if not found
}

// Listen for hash changes
function setupHashChangeListener() {
  window.addEventListener('hashchange', () => {
    const newHash = window.location.hash.replace('#', '');
    if (newHash !== currentSectionId.value) {
      currentSectionId.value = newHash;
      currentSection.value = findSection(newHash);
    }
  });
}

const currentHash = ref(window.location.hash.replace('#', '') || sections[0].id);

function updateHash() {
  currentHash.value = window.location.hash.replace('#', '');
}

// Function to handle navigation triggered by NavBar
function handleNavigation(section) {
  currentSectionId.value = section.id;
  window.location.hash = section.id; // Set hash in URL
  currentSection.value = findSection(section.id); // Update the current section directly
}

// Function to navigate to the previous section
const navigateToPrevSection = () => {
  const currentIndex = sections.findIndex(s => s.id === currentSectionId.value);
  if (currentIndex > 0) {  // Ensure there is a previous section
    const prevSection = sections[currentIndex - 1];
    handleNavigation(prevSection);  // Reuse the existing navigation handler
  }
};

// Function to navigate to the next section
const navigateToNextSection = () => {
  const currentIndex = sections.findIndex(s => s.id === currentSectionId.value);
  if (currentIndex < sections.length - 1) {
    const nextSection = sections[currentIndex + 1];
    handleNavigation(nextSection);  // Reuse the existing navigation handler
  }
};

// React to external hash changes, like forward/back navigation in browser
watch(() => window.location.hash, (newHash) => {
  const sectionId = newHash.replace('#', '');
  if (currentSectionId.value !== sectionId) {
    currentSectionId.value = sectionId;
    currentSection.value = findSection(sectionId);
  }
});

// Initialize the section based on the current hash when the component is mounted
onMounted(() => {
  setupHashChangeListener();
  if (!window.location.hash && sections.length > 0) {
    window.location.hash = sections[0].id; // Set the default hash if none is present
  } else {
    const hash = window.location.hash.replace('#', '');
    currentSectionId.value = hash;
    currentSection.value = findSection(hash);
  }
});

</script>

<style scoped>
.main-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
  width: 100vw;
  padding-top: 4rem; /* Offset for fixed header, assuming it's 4rem high */
  overflow: hidden; /* Prevents scrolling on the main container */
  font-family: 'Comic Sans MS', 'Comic Sans', Arial, sans-serif; /* Fun, child-like font */
  background-color: #6495ED; /* Bright, engaging background color */
}

@media (min-width: 1024px) {
  .main-container {
    flex-direction: row; /* Changes layout to horizontal at wider screens */
  }
}

.content {
  flex: 1;
  overflow-y: auto; /* Enables scrolling within the content area */
  padding: 5px; /* Uniform padding, may need adjustment based on content */
}

@media (min-width: 1024px) {
  .content {
    flex: 4; /* Allocates more space to the content in larger screens */
    padding: 20px 40px; /* Increased padding on larger screens for better readability */
  }
}
</style>
