<template>
  <Head title="No Invite Codes"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-col w-full my-8 p-4 gap-8">
        <h1 class="text-3xl font-semibold text-center w-full">Stay Tuned, Invite Codes Incoming!</h1>
        <p class="text-xl text-center">
          Our exclusive Invite Codes are gearing up to unlock a new level of creativity, innovation, and community vibes. Soon, you'll be able to invite your friends, family, and favorite creators/artists to join the fun. We're adding the final sparkle to ensure everything is perfect for your inviting spree.
        </p>
        <div class="flex flex-row justify-center">
          <p class="text-xl text-center my-4">
            In the meantime, there's a whole world of notTV to explore. Dive into an ocean of content that's anything but ordinary.
          </p>

        </div>
        <div class="flex flex-row justify-center">
          <button @click.prevent="appSettingStore.btnRedirect('/stream')" class="btn btn-lg bg-orange-500 hover:bg-orange-700 text-white">
            Watch notTV Now!
          </button>
          <button v-if="can.viewDashboard" @click.prevent="appSettingStore.btnRedirect('/stream')" class="btn btn-lg ml-2 bg-gray-900 hover:bg-gray-700 text-white">
            My Dashboard
          </button>
        </div>
      </div>


<!--      <div class="flex flex-col w-full my-8 p-4 gap-8">-->
<!--      <h1 class="text-3xl font-semibold text-center w-full">No invite codes!</h1>-->
<!--        <div class="flex flex-row justify-center">-->
<!--          <button @click.prevent="appSettingStore.btnRedirect('/invite_codes')"-->
<!--                  class="btn bg-orange-500 hover:bg-orange-700 text-white">Request some codes!-->
<!--          </button>-->
<!--        </div>-->
<!--      </div>-->

    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('inviteCodes.report')
const appSettingStore = useAppSettingStore()

const props = defineProps({
  can: Object,
})

const changePage = (url) => {
  if (url) {
    router.visit(url)
  }
}


</script>
