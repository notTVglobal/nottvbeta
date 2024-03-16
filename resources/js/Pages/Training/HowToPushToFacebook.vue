<template>

  <Head title="How To Push To Facebook"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>


      <!--      <header class="flex justify-between mb-3">-->
      <!--        <div class="pt-4">-->
      <!--          <h1 class="text-3xl font-semibold pb-3">My Library</h1>-->
      <!--        </div>-->

      <!--      </header>-->
      <!--      <div class="flex flex-row justify-end gap-x-4 mb-4">-->

      <!--        <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg text-black"/>-->
      <!--      </div>-->

      <!--      <div class="mb-4">-->
      <!--        <div class="p-2 text-red-600">This section is in development. Not currently working.</div>-->
      <!--        <p class="">-->
      <!--          Your notTV Library... Shows and creators you follow will appear here. Movies and episodes on your Watch Later-->
      <!--          list. And live events you're interested in. Also, items from the shop you have saved will all appear here!-->
      <!--        </p>-->
      <!--      </div>-->

      <div class="p-4">
        <div class="flex flex-row justify-end">
          <BackButton/>
        </div>
        <h1 class="text-2xl font-bold text-left mb-4">How To Push To Facebook</h1>

        <p class="mb-4">
          This guide will walk you through the process of pushing your Live Stream to Facebook.
        </p>

        <div class="space-y-6">
          <!-- Step 1: Configure Zoom -->
          <div>
            <h2 class="text-xl font-semibold mb-2">Step 1: Setup Facebook To Go Live</h2>
            <p class="mb-4">
              From your Facebook page when you post a new message click "Live Video"
            </p>
            <img
                src="https://cdn.nottv.io/public/2024/03/images/fhfNXxmKbk78IupmVByAumDM9g2WR0qzu8IJo6zt.png"
                alt="Click Live Video on Facebook when you are going to send a message"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/fhfNXxmKbk78IupmVByAumDM9g2WR0qzu8IJo6zt.png','Click Live Video on Facebook when you are going to send a message')"
            />

          </div>
          <div class="pl-4 mb-4">
            <h3 class="text-lg font-semibold mb-1">1.1 Choose Go Live or Create an Event</h3>
            <p>

            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/kKO28nkBUyNUf50YzVI3yZ01cliMJgiQOkwFwMRP.png"
                alt="Choose Go Live on Facebook"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/kKO28nkBUyNUf50YzVI3yZ01cliMJgiQOkwFwMRP.png','Choose Go Live on Facebook')"
            />
          </div>

          <div class="pl-4 mb-4">
            <h3 class="text-lg font-semibold mb-1">1.2 Copy your Stream Key and the Server URL</h3>
            <p>
              (You'll need these later.)
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/rmbz1FpuefO5DVfk0G2C6SjThZBjG5KJuYuusgpE.png"
                alt="Copy your Stream Key and the Server URL"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/rmbz1FpuefO5DVfk0G2C6SjThZBjG5KJuYuusgpE.png','Copy your Stream Key and the Server URL')"
            />
          </div>

          <div class="pl-4 mb-4">
            <h3 class="text-lg font-semibold mb-1">1.3 If the Go Live button is greyed out you need to 'Complete post details':</h3>
            <p>

            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/Ht5rRz6fibD1fVrAsyx4fY2PkSzAdh73Ym5nDsUR.png"
                alt="Check Go Live Button in Facebook if its greyed out you may need to write the post description first"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/Ht5rRz6fibD1fVrAsyx4fY2PkSzAdh73Ym5nDsUR.png','Check Go Live Button in Facebook if its greyed out you may need to write the post description first')"
            />
          </div>

          <div class="pl-4 mb-4">
            <p>
              Find the 'Edit post details' button:
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/Yr4oHtnJaA8dz9kIxuHiPzIzmxZB582Sqxsc5zst.png"
                alt="Click edit details"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/Yr4oHtnJaA8dz9kIxuHiPzIzmxZB582Sqxsc5zst.png','Click edit details')"
            />
          </div>

          <div class="pl-4 mb-4">
            <p>
              Write a description:
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/cidGqjiH8xMMMaoK8UapFypwNa2iza90sSKSo6rd.png"
                alt="Write a description"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/cidGqjiH8xMMMaoK8UapFypwNa2iza90sSKSo6rd.png','Write a description')"
            />
          </div>


          <div class="pl-4 mb-4">
            <h3 class="text-lg font-semibold mb-1">1.4 Connect the video source (if it's not connected):</h3>
            <p>
            See the next step to connect the video source.
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/t0dYwH1vnjYxjBOCxCt8pu4bQqMhduCktBcLmIPs.png"
                alt="Connect a video source"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/t0dYwH1vnjYxjBOCxCt8pu4bQqMhduCktBcLmIPs.png','Connect a video source')"
            />
          </div>

          <div>
            <h2 class="text-xl font-semibold mb-2">Step 2: Connect the video source (push your stream to Facebook):</h2>
            <p class="mb-4">
              If you start streaming to notTV before you are on your Facebook Live page and you don't see the live
              stream on Facebook you need to re-push the stream from your notTV Go Live page (details are below).
            </p>
          </div>

          <div class="pl-4 mb-4">
            <h3 class="text-lg font-semibold mb-1">2.1 Add Facebook to your Push Destinations on your notTV Go Live page:</h3>
            <p>
              Click "Add Push Destination":
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/pVgJWhcbT9mfSwXBtKA9h4UO6PZVQiGNHRH2pONb.png"
                alt="Add push destination on your notTV Go Live page"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/pVgJWhcbT9mfSwXBtKA9h4UO6PZVQiGNHRH2pONb.png','Add push destination on your notTV Go Live page')"
            />

          </div>

          <div class="pl-4 mb-4">
            <p>
              Click "Enter your Facebook Stream Key and Stream URL that you copied earlier":
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/E0vobEObFwjcbnUYKKCbuqlnX66nbIrfwJci593j.png"
                alt="Enter your Facebook Stream Key and Stream URL"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/E0vobEObFwjcbnUYKKCbuqlnX66nbIrfwJci593j.png','Enter your Facebook Stream Key and Stream URL')"
            />

          </div>

          <div class="pl-4 mb-4">
            <h3 class="text-lg font-semibold mb-1">If you don't see the live video in Facebook:</h3>
            <p>

            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/N1SpqaDIs96UEIyI5kYXmxJpkfUUVh3KLRp6lOzK.png"
                alt="Connect streaming software to go live"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/N1SpqaDIs96UEIyI5kYXmxJpkfUUVh3KLRp6lOzK.png','Connect streaming software to go live')"
            />

          </div>

          <div class="pl-4 mb-4">
            <p>
              1. Stop the push to Facebook if it's started:
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/hMZ0G6Ok84MLZ4PVbgwYwimMR3klLLBuQj1rdcq3.png"
                alt="Stop Push"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/hMZ0G6Ok84MLZ4PVbgwYwimMR3klLLBuQj1rdcq3.png','Stop Push')"
            />

          </div>

          <div class="pl-4 mb-4">
            <p>
              2. Start the push to Facebook:
            </p>

            <img
                src="https://cdn.nottv.io/public/2024/03/images/71B6TzoPQxYjA0600uRiOya8dCcavkdlXKG3MicD.png"
                alt="Start Push"
                class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
                @click="setImage('https://cdn.nottv.io/public/2024/03/images/71B6TzoPQxYjA0600uRiOya8dCcavkdlXKG3MicD.png','Start Push')"
            />
          </div>
        </div>

        <div class="pl-4 mb-4 mt-4">
          <h3 class="text-lg font-semibold mb-1">The video should start to connect:</h3>
          <p>

          </p>

          <img
              src="https://cdn.nottv.io/public/2024/03/images/eRKCAQnxAr8u34It2eAqossVU3Z6sg2IwXHSchj5.png"
              alt="Connecting live video"
              class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
              @click="setImage('https://cdn.nottv.io/public/2024/03/images/eRKCAQnxAr8u34It2eAqossVU3Z6sg2IwXHSchj5.png','Connecting live video')"
          />

        </div>

        <div class="pl-4 mb-4">
          <p>
            You should see the video appear:
          </p>

          <img
              src="https://cdn.nottv.io/public/2024/03/images/qm3BI3KyGCT0Z8P0vMsLp7SJc19xkYZmKhc6VJZa.png"
              alt="Preview the live video"
              class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
              @click="setImage('https://cdn.nottv.io/public/2024/03/images/qm3BI3KyGCT0Z8P0vMsLp7SJc19xkYZmKhc6VJZa.png','Preview the live video')"
          />

        </div>

        <div class="pl-4 mb-4">
          <h3 class="text-lg font-semibold mb-1">Step 3: Go Live</h3>
          <p>
            Push the blue Go Live button:
          </p>

          <img
              src="https://cdn.nottv.io/public/2024/03/images/uSRaLddoeZD1QxMP2Hq782vrQvnIu8UIRPTRnMOG.png"
              alt="Push the Go Live button"
              class="cursor-pointer w-64 object-cover mt-2 border-black border-2"
              @click="setImage('https://cdn.nottv.io/public/2024/03/images/uSRaLddoeZD1QxMP2Hq782vrQvnIu8UIRPTRnMOG.png','Push the Go Live button')"
          />

        </div>

      </div>

      <ImageLightboxModal
          v-if="selectedImageUrl"
          :imageUrl="selectedImageUrl"
          :imageAlt="selectedImageAlt"
          @closed="closeImage"/>

    </div>
  </div>

</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import Message from '@/Components/Global/Modals/Messages'
import { computed, ref, watchEffect } from 'vue'
import BackButton from '@/Components/Global/Buttons/BackButton'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useClipboard } from '@vueuse/core'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ImageLightboxModal from '@/Components/Global/Images/ImageLightboxModal.vue'

usePageSetup('training.streamToNotTV')

const appSettingStore = useAppSettingStore()
const goLiveStore = useGoLiveStore()
let selectedShow = ref(null)

const page = usePage()

let props = defineProps({
  can: Object,
})

const showCopiedFullUrl = ref(false)
const showCopiedRtmpUri = ref(false)
const showCopiedStreamKey = ref(false)
const {copy} = useClipboard()

const fullUrl = ref('')
const rtmpUri = ref('')
const streamKey = ref('')
const appUrl = page.props.value.appUrl

const setSelectedShow = async () => {
  await goLiveStore.fetchShows().then(
      selectedShow = computed(() => goLiveStore.selectedShow),
  )
}
setSelectedShow()

// Reactively update URLs when the store updates
watchEffect(() => {
  if (goLiveStore.rtmpUri) {
    rtmpUri.value = goLiveStore.rtmpUri + 'live/'
    // Check if it's an episode or a selected show and update accordingly
    if (goLiveStore.isEpisode && goLiveStore.episode?.mist_stream_wildcard?.name) {
      streamKey.value = goLiveStore.episode.mist_stream_wildcard.name
    } else if (!goLiveStore.isEpisode && goLiveStore.selectedShow?.mist_stream_wildcard?.name) {
      streamKey.value = goLiveStore.selectedShow.mist_stream_wildcard.name
    }
    if (goLiveStore.isEpisode && goLiveStore.episode?.slug) {
      fullUrl.value = `${appUrl}/shows/${selectedShow.value?.slug}/episode/${goLiveStore.episode.slug}`
    } else if (!goLiveStore.isEpisode && selectedShow.value?.slug) {
      fullUrl.value = `${appUrl}/shows/${selectedShow.value?.slug}`
    }
  }
})

// Function to handle the copy action and display the "copied" message for each type
const copyFullUrl = () => {
  copy(fullUrl.value)
  showCopiedFullUrl.value = true
  setTimeout(() => showCopiedFullUrl.value = false, 1000)
}

const copyRtmpUri = () => {
  copy(rtmpUri.value)
  showCopiedRtmpUri.value = true
  setTimeout(() => showCopiedRtmpUri.value = false, 1000)
}

const copyStreamKey = () => {
  copy(streamKey.value)
  showCopiedStreamKey.value = true
  setTimeout(() => showCopiedStreamKey.value = false, 1000)
}

const selectedImageUrl = ref(null);
const selectedImageAlt = ref(null);

const closeImage = () => {
  selectedImageUrl.value = null
  selectedImageAlt.value = null
}

const setImage = (imageUrl, imageAlt) => {
  selectedImageUrl.value = imageUrl
  selectedImageAlt.value = imageAlt
}


</script>

<style scoped>
.copied-message-fade {
  animation: fadeOut 2s forwards;
}

@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
</style>