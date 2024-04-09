<template>
  <div>
    <form class="px-4 pb-4" @submit.prevent="submit">
      <h2 class="text-2xl font-bold mb-4">First Play Video Source</h2>
      <button @click.prevent="clearFirstPlayCacheData"
              class="btn btn-warning btn-sm mb-4">
        Clear First Play Data Cache
      </button>
      <!-- Toggle -->
      <div class="form-control mt-4">
        <label class="label cursor-pointer flex justify-start items-center space-x-3 mb-2">
          <span class="label-text">Use Custom First Play Video</span>
          <input type="checkbox" ref="useCustomVideoCheckbox"  class="toggle toggle-primary" v-model="adminStore.firstPlaySettings.useCustomVideo" @click="blurCheckbox"/>
        </label>
        <!-- Display error for useCustomVideo if exists -->
        <div v-if="adminStore.validationErrors['useCustomVideo']" class="text-xs text-red-600 mt-1">
          {{ adminStore.validationErrors['useCustomVideo'][0] }}
        </div>
      </div>

      <!-- Custom Video Inputs -->
      <div v-if="adminStore.firstPlaySettings.useCustomVideo" class="space-y-3">
        <!-- First Play Video Source -->
        <div class="mb-6">
          <label for="customVideoSource" class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300">
            First Play Video Source
          </label>
          <input v-model="adminStore.firstPlaySettings.customVideoSource" class="input input-bordered w-full" id="first_play_video_source">
          <!-- Display error for videoSource if exists -->
          <div v-if="adminStore.validationErrors['customVideoSource']" class="text-xs text-red-600 mt-1">
            {{ adminStore.validationErrors['customVideoSource'][0] }}
          </div>
          <span class="text-xs">e.g., https://mist.nottv.io/hls/test/index.m3u8</span>
        </div>

        <!-- First Play Video Source Type -->
        <div class="mb-6">
          <label for="customVideoSourceType" class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300">
            First Play Video Source Type
          </label>
          <input v-model="adminStore.firstPlaySettings.customVideoSourceType" class="input input-bordered w-full" id="first_play_video_source_type">
          <!-- Display error for videoSourceType if exists -->
          <div v-if="adminStore.validationErrors['customVideoSourceType']" class="text-xs text-red-600 mt-1">
            {{ adminStore.validationErrors['customVideoSourceType'][0] }}
          </div>
          <span class="text-xs">e.g., video/mp4 or application/x-mpegURL</span>
        </div>

        <!-- First Play Video Name -->
        <div class="mb-6">
          <label for="customVideoName" class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300">
            First Play Video Name
          </label>
          <input v-model="adminStore.firstPlaySettings.customVideoName" class="input input-bordered w-full" id="first_play_video_name">
          <!-- Display error for videoName if exists -->
          <div v-if="adminStore.validationErrors['customVideoName']" class="text-xs text-red-600 mt-1">
            {{ adminStore.validationErrors['customVideoName'][0] }}
          </div>
        </div>
      </div>

      <!-- Channel Dropdown -->
      <div v-else class="">
        <label for="channelId" class="label block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300">Select
          Channel</label>
        <select v-model="adminStore.firstPlaySettings.channelId" class="select select-bordered w-full" id="channel_id">
          <option disabled value="">Select a channel</option>
          <option v-for="channel in channelStore.activeChannels" :key="channel.id" :value="channel.id">{{
              channel.name
            }}
          </option>
        </select>
        <!-- Display error for channelId if exists -->
        <div v-if="adminStore.validationErrors['channelId']" class="text-xs text-red-600 mt-1">
          {{ adminStore.validationErrors['channelId'][0] }}
        </div>
      </div>

    </form>



  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
// import { useForm } from '@inertiajs/inertia-vue3'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useAdminStore } from '@/Stores/AdminStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { Inertia } from '@inertiajs/inertia'

const notificationStore = useNotificationStore()
const adminStore = useAdminStore()
const channelStore = useChannelStore()

// let form = useForm({
//   useCustomVideo: Boolean,
//   customVideoSource: '',
//   customVideoSourceType: '',
//   customVideoName: '',
//   channelId: '',
// })

onMounted(async () => {
  await channelStore.getChannels()
  await adminStore.fetchFirstPlaySettings()
})

const useCustomVideoCheckbox = ref(null);

const blurCheckbox = () => {
  if (useCustomVideoCheckbox.value) {
    useCustomVideoCheckbox.value.blur();
  }
};

const selectedChannelId = ref('')

let clearFirstPlayCacheData = () => {
  Inertia.post(route('admin.clear-first-play-data-cache'))
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
}

</script>
