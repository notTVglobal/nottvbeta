<template>
  <div>
    <div class="w-full px-4 mx-auto text-center pt-4">
      <p class="px-2 font-bold">{{ now }}</p>
    </div>

    <div class="flex flex-col items-center mb-6 pt-4">
      <h1 class="w-full text-center text-3xl font-bold text-black">Dashboard for Creators</h1>
      <p class="flex flex-wrap justify-center items-center w-full text-center">
        <span class="mr-1">Your timezone:</span>
        <span>{{ userStore.canadianTimezone }}</span>
      </p>

    </div>

    <div class="w-full flex flex-wrap-reverse justify-center mx-auto gap-2 mb-6">
      <button
          v-if="can.viewAdmin"
          @click="appSettingStore.btnRedirect(`/admin/settings`)"
          class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
      >Admin Settings
      </button>
      <button
          v-if="can.viewNewsroom"
          @click="appSettingStore.btnRedirect(`/newsroom`)"
          class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
      >Newsroom
      </button>
      <button
          @click="appSettingStore.btnRedirect(`/news/upload`)"
          class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400 cursor-not-allowed"
          disabled
      >Share News
      </button>
      <button
          @click="appSettingStore.btnRedirect(`/invite`)"
          class="bg-orange-600 hover:bg-orange-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
      >Invite Creator
      </button>
      <button
          @click="appSettingStore.btnRedirect(`/videoupload`)"
          class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
      >Upload Video
      </button>
      <button
          v-if="hasTeams"
          @click="appSettingStore.btnRedirect(`/golive`)"
          class="bg-red-600 hover:bg-red-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
      >Go Live
      </button>

    </div>
  </div>
</template>
<script setup>
import { useNow } from '@vueuse/core'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from "@/Stores/UserStore"

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const now = useNow()

defineProps({
  can: Object,
  hasTeams: Boolean,
})

</script>
