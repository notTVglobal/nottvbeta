<template>
  <Head :title="`Video Share`"/>

  <div class="flex flex-col h-screen w-full bg-black overflow-x-hidden overflow-y-auto">
    <PublicNavigationMenu/>
    <PublicResponsiveNavigationMenu/>

    <div class="video-player-page flex flex-col items-center justify-center bg-black text-white relative mb-24">
      <div class="fixed top-20 left-10 px-3 py-2 z-50">
        <div class="flex flex-col gap-2">
          <div>{{ video.filename }}</div>
          <div class="flex gap-2 items-center">
            <div class="min-w-[2rem]">
              <img v-if="video.user.profile_photo_path"
                   :src="'/storage/' + video.user.profile_photo_path" class="rounded-full h-8 w-8 object-cover">
              <img v-if="!video.user.profile_photo_path"
                   :src="video.user.profile_photo_url" class="rounded-full h-8 w-8 object-cover bg-gray-300">
            </div>
            <div>{{ video.user.name }}</div>
          </div>

        </div>
      </div>
      <div class="fixed top-20 right-10 px-3 py-2 z-50">
        <div class="flex gap-2">
          <button @click="beginDownload"
                  class="flex bg-orange-500 text-white font-semibold px-4 py-2 hover:bg-orange-400 rounded transition ease-in-out duration-150 items-center">
            <font-awesome-icon icon="fa-share" class="mr-2"/>
            Download
          </button>
          <ShareButton :model="video"/>
        </div>
      </div>
      <div class="video-container w-full max-w-screen-lg mt-16">
        <video-js-share :id="`videoPlayer`"
                        :source="videoSource"
                        :sourceType="videoType"
                        class="vjs-default-skin"
                        controls
                        preload="auto"
                        width="100%"
                        height="500"/>
      </div>

      <div v-if="!$page.props.auth.user" class="signup-box-container flex items-center justify-center">
        <div class="signup-box p-4 bg-gray-800 text-white rounded-lg shadow-lg">
          <div class="flex w-full justify-center mb-2">
            <img :src="`/storage/images/Ping.png`" alt="notTV's Ping" class="w-10 h-10"/>
          </div>
          <h2 class="text-lg font-semibold">Sign Up for notTV</h2>
          <p class="mt-2">Join our community and stay updated with the latest content.</p>
          <form @submit.prevent="handleSignup" class="mt-4 flex flex-col">
            <input type="email" v-model="email" placeholder="Enter your email"
                   class="p-2 rounded bg-gray-900 text-white" required>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded">Sign Up
            </button>
          </form>
        </div>
      </div>
    </div>
    <Footer/>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import CopyClipboard from '@/Components/Global/Text/CopyClipboard.vue'
import VideoJsShare from '@/Components/Global/VideoPlayer/VideoJs/VideoJsShare.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()

const page = usePage().props

const props = defineProps({
  video: Object,
  ulid: String,
  videoSource: String,
  videoType: String,
})

const title = ref('Your Video Title')
const shareUrl = `${page.appUrl}/video/${props.ulid}`
const email = ref('')

const handleSignup = () => {
  // Handle signup logic
  alert('Thank you for signing up!')
}


const beginDownload = async () => {
  const url = props.videoSource

  try {
    // Fetch the file from the URL
    const response = await fetch(url)
    const blob = await response.blob()

    // Create a link element
    const downloadLink = document.createElement('a')
    downloadLink.href = URL.createObjectURL(blob)
    downloadLink.download = props.video.filename || 'downloaded_video'

    // Append the link to the document body
    document.body.appendChild(downloadLink)

    // Trigger the download
    downloadLink.click()

    // Remove the link from the document
    document.body.removeChild(downloadLink)

    // Revoke the object URL
    URL.revokeObjectURL(downloadLink.href)
  } catch (error) {
    console.error('Download failed', error)
  }
}
appSettingStore.pageReload = true

</script>

<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

<style scoped>
.video-player-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: black;
  color: white;
  position: relative;
}

.video-container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

.signup-box-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.signup-box {
  width: 80%;
  max-width: 400px;
  text-align: center;
}
</style>
