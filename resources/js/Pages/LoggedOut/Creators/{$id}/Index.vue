<template>
  <div>
    <div id="topDiv" class="place-self-center flex flex-col h-screen">
      <div class="min-h-screen w-full bg-gray-900 text-gray-50 mt-16 overflow-y-scroll">
        <PublicNavigationMenu/>
        <PublicResponsiveNavigationMenu/>

        <ShowCreators :creator="creator"/>

        <Footer/>
      </div>
    </div>
  </div>
</template>

<script setup>
import ShowCreators from '@/Components/Pages/Creators/ShowCreators.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { defineProps, onMounted } from 'vue'

const props = defineProps({
  creator: Object,
})

onMounted(() => {
  // Only scroll into view if there are no query strings
  const topDiv = document.getElementById('topDiv')
  if (topDiv) {
    topDiv.scrollIntoView()
  }
})
</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>
