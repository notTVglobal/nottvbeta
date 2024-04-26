<template>
  <Head :title="`Newsletter Signup`"/>
  <div id="topDiv" class="place-self-center flex flex-col overscroll-x-none pb-64 h-screen">
    <div class="w-full min-h-screen bg-gray-900 flex flex-col gap-y-3 place-self-center text-white mt-16 overflow-y-scroll">
    <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu />

      <main class="mx-auto border-b border-gray-800">
        <BrevoForm />
      </main>

      <Footer />
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
// import { useAppSettingStore } from '@/Stores/AppSettingStore'
// import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import Footer from '@/Components/Global/Layout/Footer'
import BrevoForm from '@/Components/Pages/NewsletterSignup/BrevoForm.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'

// const appSettingStore = useAppSettingStore()
// const videoPlayerStore = useVideoPlayerStore()

// appSettingStore.noLayout = true
// appSettingStore.noVideo = true
// appSettingStore.currentPage = 'newsletterSignup'

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
})


// Watch for changes in the loggedIn state of appSettingStore
// watch(() => appSettingStore.loggedIn, (loggedIn) => {
//   appSettingStore.noLayout = !loggedIn;
// })
//
// const props = defineProps({
//
// })

// const marginTopClass = computed(() => {
//   return appSettingStore.loggedIn ? '' : 'mt-16';
// });



</script>
<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
  layout: NoLayout,
}
</script>