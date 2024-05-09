<template>

  <Head title="How To Push To Facebook"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

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

    </div>
  </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'
import BackButton from '@/Components/Global/Buttons/BackButton'

usePageSetup('training.streamToNotTV')

const appSettingStore = useAppSettingStore()

const setImage = (imageUrl, imageAlt) => {
  appSettingStore.imageLightboxModal.imageUrl = imageUrl;
  appSettingStore.imageLightboxModal.imageAlt = imageAlt;
  appSettingStore.showImageLightboxModal = true
}


</script>