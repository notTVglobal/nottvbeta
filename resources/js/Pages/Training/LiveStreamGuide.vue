  <template>
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu />
      <!-- Conditionally render TitlePage or MainContent based on whether the user has started -->
      <TitlePage v-if="!hasStarted" @navigate="handleStart"/>
      <MainContent v-else :currentSectionId="currentSectionId"/>
    </div>
  </template>
  <script setup>
  import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
  import MainContent from '@/Components/Pages/Training/LiveStreamGuide/MainContent.vue'
  import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
  import TitlePage from '@/Components/Pages/Training/LiveStreamGuide/TitlePage.vue'
  import { onMounted, ref } from 'vue'

  // State to track if the user has started the guide
  const hasStarted = ref(false);
  const currentSectionId = ref(''); // Default to intro

  // Function to handle the start from the TitlePage
  function handleStart(sectionId = 'intro') {
    currentSectionId.value = sectionId;
    hasStarted.value = true;
  }

  // Detect if the user lands with a specific section in the URL hash
  onMounted(() => {
    const hash = window.location.hash.replace('#', '');
    if (hash) {
      currentSectionId.value = hash;
      hasStarted.value = true; // Bypass the TitlePage if a hash is present
    }
  });

  // function handleNavigation(sectionId) {
  //   currentSectionId.value = sectionId; // Use the provided sectionId or default to 'intro'
  //   hasStarted.value = true;
  // }
  </script>
  <script>
  import NoLayout from '@/Layouts/NoLayout';

  export default {
    layout: NoLayout,
  }
  </script>