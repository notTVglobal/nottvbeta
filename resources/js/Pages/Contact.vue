<template>
  <Head :title="`Contact Us`"/>
  <div id="topDiv" ></div>
  <div :class="marginTopClass">
    <PublicNavigationMenu v-if="!appSettingStore.loggedIn" class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu v-if="!appSettingStore.loggedIn"/>

    <div class="w-full bg-gray-900 flex flex-col gap-y-3 place-self-center text-white">

      <div class="pt-10 text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">Contact Us</div>
      <main class="mx-auto border-b border-gray-800">

      Contact form goes here

      </main>

      <Footer v-if="!appSettingStore.loggedIn"/>
    </div>
  </div>
</template>




<script setup>
import { computed, onMounted, watch } from 'vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import Footer from '@/Components/Global/Layout/Footer'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.noLayout = true
appSettingStore.noVideo = true
appSettingStore.currentPage = 'newsletterSignup'

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
})


// Watch for changes in the loggedIn state of appSettingStore
watch(() => appSettingStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn;
})

const props = defineProps({

})

const marginTopClass = computed(() => {
  return appSettingStore.loggedIn ? '' : 'mt-16';
});



</script>
