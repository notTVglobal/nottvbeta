<template>
  <nav class="nav-bar">
    <div @click="toggleDropdown" class="dropdown-button" v-if="isMobile">
      <font-awesome-icon icon="bars" />
      {{ sections.find(section => section.id === currentHash.value)?.name }}
    </div>
    <ul v-show="dropdownOpen || !isMobile">
      <li v-for="(section, index) in sections" :key="section.id"
          :class="{ 'active': section.id === activeSection  }"
          @click="handleNavigation(section)">
        <font-awesome-icon :icon="`fa-${index + 1}`" />
        {{ section.name }}
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
  sections: Array,
  activeSection: String
});
const emit = defineEmits(['navigate']);

const currentHash = ref(window.location.hash.replace('#', ''));
const dropdownOpen = ref(false);
const isMobile = ref(window.innerWidth < 1020);

// Computed property for active section ID is not necessary if we use currentHash directly in the template
// Computed property to get the current section's name
const currentSectionName = computed(() => {
  const section = props.sections.find(s => s.id === currentHash.value);
  return section ? section.name : 'Select Section';
});

function toggleDropdown() {
  dropdownOpen.value = !dropdownOpen.value;
}

function handleNavigation(section) {
  // Directly setting the hash will automatically update currentHash via the watcher
  window.location.hash = section.id;
  emit('navigate', section);
  dropdownOpen.value = false; // Close the dropdown upon selection
}

// Listener for hash changes
function onHashChange() {
  currentHash.value = window.location.hash.replace('#', '');
}

// Setup to handle resizing properly
window.addEventListener('resize', () => {
  isMobile.value = window.innerWidth < 1020;
});

// Watch hash changes to update currentHash
watch(() => window.location.hash, (newHash) => {
  currentHash.value = newHash.replace('#', '');
});

onMounted(() => {
  window.addEventListener('hashchange', onHashChange);
  isMobile.value = window.innerWidth < 1020;
});

onBeforeUnmount(() => {
  window.removeEventListener('hashchange', onHashChange);
});

</script>


<style scoped>
.nav-bar {
  position: relative;
}

.dropdown-button {
  display: none; /* Hidden by default */
  cursor: pointer;
  padding: 10px;
  background-color: #FFD700;
  border-radius: 8px;
  text-align: center;
}

ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.nav-bar li {
  cursor: pointer;
  background-color: #FFD700;
  margin: 10px;
  padding: 10px;
  border-radius: 8px;
  text-align: center;
}

.nav-bar li.active,
.nav-bar li:hover {
  background-color: #FF6347;
}

@media (max-width: 1020px) {
  .dropdown-button {
    display: flex; /* Show on smaller screens */
    justify-content: space-between;
    align-items: center;
  }

  ul {
    display: none; /* Hide by default on smaller screens */
    position: absolute;
    width: 100%;
    background-color: white;
    z-index: 1000;
  }

  ul[v-show="true"] {
    display: block; /* Show when active, controlled by Vue */
  }
}
</style>


