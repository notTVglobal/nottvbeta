  <template>
    <Head title="Live Streaming Guide"/>
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
  import { onMounted, onUnmounted, ref, watch } from 'vue'

  // State to track if the user has started the guide
  const hasStarted = ref(false);
  const currentSectionId = ref(''); // Default to intro

  // Function to update the hash
  const updateHash = () => {
    currentSectionId.value = window.location.hash.replace('#', '');
  };

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
    // Add event listener for hash changes
    window.addEventListener('hashchange', updateHash);
  });

  // Cleanup event listener on unmount
  onUnmounted(() => {
    window.removeEventListener('hashchange', updateHash);
  });

  watch(currentSectionId, (newHash, oldHash) => {
    if (newHash !== oldHash) {
      requestAnimationFrame(() => {
        const topDiv = document.getElementById("topDiv");
        if (topDiv) {
          topDiv.scrollIntoView({ behavior: 'auto' });
        } else {
          window.scrollTo(0, 0);
        }
      });
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