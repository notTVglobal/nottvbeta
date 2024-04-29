<template>
  <nav class="nav-bar">
    <div @click="toggleDropdown" class="dropdown-button" v-if="appSettingStore.isSmallScreen">
      <font-awesome-icon icon="bars" /> <!-- FontAwesome icon for menu -->
      {{ currentSectionName }}
    </div>
    <ul v-show="dropdownOpen || !appSettingStore.isSmallScreen">
      <li v-for="(section, index) in sections" :key="section.id"
          :class="{ 'active': section.id === activeSectionId }"
          @click="handleNavigation(section)">
        <font-awesome-icon :icon="`fa-${index + 1}`" />
        {{ section.name }}
      </li>
    </ul>
  </nav>
</template>
<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const appSettingStore = useAppSettingStore()

const props = defineProps({ sections: Array });
const emit = defineEmits(['navigate']);

const currentHash = ref(window.location.hash.replace('#', ''));
const dropdownOpen = ref(false);

appSettingStore.checkScreenSize()

// Reactive window size check
const updateMobileState = () => {
  // isMobile.value = window.innerWidth < 1020;
  if (!appSettingStore.isSmallScreen) {
    dropdownOpen.value = false; // Ensure dropdown is closed when not in mobile view
  }
};

// window.addEventListener('resize', updateMobileState);

const activeSectionId = computed(() => currentHash.value || props.sections[0].id);
const currentSectionName = computed(() => {
  const section = props.sections.find(s => s.id === activeSectionId.value);
  return section ? section.name : 'Select a section';
});

function toggleDropdown() {
  if (appSettingStore.isSmallScreen) { // Ensure toggle only works in mobile view
    dropdownOpen.value = !dropdownOpen.value;
  }
}

function handleNavigation(section) {
  currentHash.value = section.id;
  window.location.hash = section.id;
  emit('navigate', section);
  dropdownOpen.value = false; // Close dropdown after navigation
}

// Function to update the current hash based on window.location.hash
function updateCurrentHash() {
  currentHash.value = window.location.hash.replace('#', '');
}


watch(() => window.location.hash, newHash => {
  currentHash.value = newHash.replace('#', '');
});

onMounted(() => {
  updateMobileState(); // Initial check on component mount
  // Set up the hashchange event listener when the component mounts
  window.addEventListener('hashchange', updateCurrentHash);
  // if (!window.location.hash && props.sections.length > 0) {
  //   window.location.hash = props.sections[0].id;
  // }
});

onUnmounted(() => {
  // Remove the hashchange event listener when the component unmounts
  window.removeEventListener('hashchange', updateCurrentHash);
});

// Initial check in case the URL already has a hash when the component mounts
updateCurrentHash();
</script>


<style scoped>
.nav-bar {
  position: relative;
}

.dropdown-button {
  //display: none; /* Hidden by default */
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
  display: block; /* Always visible on larger screens */
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
    display: block; /* Show on smaller screens */
  }

  ul {
    position: absolute;
    width: 100%;
    background-color: white;
  }

  ul[v-show="true"] {
    display: block; /* Show when active */
  }
}
</style>

