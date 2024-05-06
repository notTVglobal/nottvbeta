<template>

  <Head :title="show.name"/>

  <div id="topDiv" class="place-self-center flex flex-col h-screen">
    <div class="min-h-screen w-full bg-gray-900 text-gray-50 pt-6 mt-16 overflow-y-scroll">

      <PublicNavigationMenu/>
      <PublicResponsiveNavigationMenu/>

      <ShowIdIndexHeader :show="show" :team="team"/>

      <ShowIdIndexMain :show="show" :team="team" :episodes="episodes" :creators="creators" />

      <Footer/>

    </div>
  </div>

</template>

<script setup>
import { onMounted } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ShowIdIndexHeader from '@/Components/Pages/Shows/Elements/ShowIdIndexHeader.vue'
import ShowIdIndexMain from '@/Components/Pages/Shows/Elements/ShowIdIndexMain.vue'

const appSettingStore = useAppSettingStore()

let props = defineProps({
  show: Object,
  episodes: Object,
  creators: Object,
  team: Object,
})

appSettingStore.currentPage = `teams.${props.team.slug}`
appSettingStore.setPrevUrl()

// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({behavior: 'smooth'})
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({top: 0, behavior: 'smooth'})
    }
  })
}
scrollToTop() // Optionally scroll to top when the component mounts

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>




