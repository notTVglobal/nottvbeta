<template>
  <div class="px-4 pt-4 bg-white rounded-lg shadow-lg relative flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Create New Playlist</h2>

    <div class="flex-grow overflow-auto pr-2">
      <form @submit.prevent="createPlaylist" class="space-y-4">
        <div>
          <label for="name" class="block font-medium text-gray-700">Name:</label>
          <input type="text" v-model="name" id="name" class="input input-bordered w-full mt-1" required>
        </div>
        <div>
          <label for="description" class="block font-medium text-gray-700">Description:</label>
          <textarea v-model="description" id="description" class="input input-bordered w-full mt-1"></textarea>
        </div>
        <div>
          <label for="url" class="block font-medium text-gray-700">URL:</label>
          <input type="text" v-model="url" id="url" class="input input-bordered w-full mt-1">
        </div>
        <div>
          <label for="type" class="block font-medium text-gray-700">Type:</label>
          <select v-model="type" id="type" class="input input-bordered w-full mt-1">
            <option value="regular">Regular</option>
            <option value="event">Event</option>
            <option value="special">Special</option>
          </select>
        </div>
        <div>
          <label for="status" class="block font-medium text-gray-700">Status:</label>
          <select v-model="status" id="status" class="input input-bordered w-full mt-1">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label for="start_dateTime" class="block font-medium text-gray-700">
            Start DateTime: <span class="text-sm text-gray-500">(optional)</span>
          </label>
          <div class="flex">
            <input type="datetime-local" v-model="store.startTime" id="start_dateTime"
                   class="input input-bordered w-full mt-1">
            <button type="button" @click.prevent="clearStartDateTime"
                    class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Clear
            </button>
            <button type="button" @click.prevent="setStartDateTimeNow"
                    class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Now
            </button>
          </div>
        </div>
        <div>
          <label for="end_dateTime" class="block font-medium text-gray-700">
            End DateTime: <span class="text-sm text-gray-500">(optional)</span>
          </label>
          <div class="flex">
            <input type="datetime-local" v-model="store.endTime" id="end_dateTime"
                   class="input input-bordered w-full mt-1">
            <button type="button" @click.prevent="clearEndDateTime"
                    class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Clear
            </button>
            <button type="button" @click.prevent="setEndDateTimeNow"
                    class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Now
            </button>
          </div>
        </div>
        <div>
          <label for="priority" class="block font-medium text-gray-700">Priority:</label>
          <input type="number" v-model="priority" id="priority" class="input input-bordered w-full mt-1">
        </div>
        <div>
          <label for="repeat_mode" class="block font-medium text-gray-700">Repeat Mode: <span
              class="text-sm text-gray-500">(What happens when the playlist reaches the end?)</span></label>
          <select v-model="repeat_mode" id="repeat_mode" class="input input-bordered w-full mt-1">
            <option value="repeat_all">Repeat All</option>
            <option value="repeat_last">Repeat Last One Only</option>
            <option value="shuffle">Shuffle</option>
            <option value="stop">Stop</option>
            <option value="next_playlist">Play A Different Playlist</option>
          </select>
        </div>
        <div v-if="repeat_mode === 'next_playlist'">
          <label for="next_playlist" class="block font-medium text-gray-700">Select Playlist:</label>
          <select v-model="next_playlist_id" id="next_playlist" class="input input-bordered w-full mt-1">
            <option v-for="playlist in store.playlists" :key="playlist.id" :value="playlist.id">
              {{ playlist.name }}
            </option>
          </select>
        </div>
        <div class="mt-6 pt-3 border-t">
          <h3 class="text-lg font-bold mb-2">Schedule Items</h3>
          <div class="flex flex-wrap gap-2">
            <div class="mb-4" v-if="store.hasRemovedItems">
              <button @click.prevent="clearRemovedItems" class="btn btn-sm btn-danger">Clear All Removed Items</button>
            </div>
            <div class="mb-4" v-if="store.conflictCount > 0">
              <button @click.prevent="store.resolveConflicts" class="btn btn-sm btn-warning">Resolve Conflicts</button>
            </div>
            <div class="mb-4" v-if="store.conflictCount === 0 && store.gapCount > 0">
              <button @click.prevent="store.insertGaps()" class="btn btn-sm btn-danger">Insert Gaps</button>
            </div>
            <div class="mb-4" v-if="store.scheduleItems.length > 0">
              <button @click.prevent="store.removeAllItems()" class="btn btn-sm btn-danger">Remove All Items</button>
            </div>
          </div>
          <ul :class="isSmallScreen ? 'space-y-4' : 'divide-y divide-gray-200 space-y-4'">
            <li
                v-for="(item, index) in store.scheduleItemsWithUserTimezone"
                :key="item.id"
                :class="getListItemClass(item.conflict, item.removed)"
            >
              <CreatePlaylistItemCard v-if="!item.removed && item.type !== 'gap'" :item="item" :index="index"
                                      @removeItem="removeItem"/>
              <CreatePlaylistGapCard v-if="item.type === 'gap'" :item="item"
                                     @openAddContentModal="openAddContentModal"/>
              <CreatePlaylistRemovedCard v-if="item.removed" :item="item" :index="index" @addItem="addItem"/>
            </li>
          </ul>
        </div>
        <div class="sticky bottom-0 left-0 w-full p-4 bg-white border-t border-gray-200">
          <p v-if="error" class="py-3 text-red-500">{{ error }}</p>
          <div class="flex justify-between">
            <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Create
            </button>
            <button @click.prevent="useSchedule"
                    class="btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
              <span v-if="store.loadingSchedules" class="loading loading-spinner"/>
              <span v-else>Use Schedule</span>
            </button>
          </div>
          <div v-if="store.conflictCount > 0 || store.gapCount > 0" class="mt-2">
            <span class="text-sm text-red-600 font-semibold">Conflicts: {{ store.conflictCount }}</span>
            <span class="text-sm text-orange-600 font-semibold ml-4">Gaps: {{ store.gapCount }}</span>
            <span v-if="store.processing" class="ml-6 text-sm text-gray-600 font-semibold">Processing <span
                class="loading loading-dots text-info"></span> Please wait</span>
          </div>
        </div>
      </form>
    </div>
    <AddContentModal
        v-if="showModal"
        :showModal="showModal"
        :gapDuration="selectedGapDuration"
        :startDateTime="startDateTime"
        @closeModal="closeModal"
    />
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import { useChannelPlaylistStore } from '@/Stores/ChannelPlaylistStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useUserStore } from '@/Stores/UserStore'
import CreatePlaylistItemCard from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylistItemCard.vue'
import CreatePlaylistGapCard from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylistGapCard.vue'
import CreatePlaylistRemovedCard from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylistRemovedCard.vue'
import AddContentModal from '@/Components/Pages/Admin/ChannelPlaylists/AddContentModal.vue'

dayjs.extend(utc)

const store = useChannelPlaylistStore()
const notificationStore = useNotificationStore()
const userStore = useUserStore()

const isSmallScreen = computed(() => userStore.isSmallScreen)

const getListItemClass = (conflict, removed) => {
  if (removed) {
    return isSmallScreen.value
        ? 'flex flex-col p-2 border-b border-gray-200 space-y-2 bg-gray-100'
        : 'p-4 space-y-2 bg-gray-100'
  }

  return isSmallScreen.value
      ? 'flex flex-col p-2 border-b border-gray-200 space-y-2'
      : `p-4 space-y-2 ${conflict ? 'bg-red-100' : ''}`
}

const showModal = ref(false)
const selectedGapDuration = ref(0)
const startDateTime = ref('')
const name = ref('')
const description = ref('')
const url = ref('')
const type = ref('regular')
const status = ref('active')
const priority = ref(1)
const repeat_mode = ref('repeat_all')
const next_playlist_id = ref(null)
const scheduleItems = ref([])
const error = ref(null)

const createPlaylist = async () => {
  // Validate start and end times
  if (dayjs(store.startTime).isSameOrAfter(dayjs(store.endTime))) {
    notificationStore.setGeneralServiceNotification('Invalid Date Time', 'End time must be after start time.')
    return
  }

  // Check for conflicts, gaps, and empty schedule items
  if (store.conflictCount > 0) {
    notificationStore.setGeneralServiceNotification('Conflicts Present', 'Please resolve conflicts before creating the playlist.')
    return
  }
  if (store.gapCount > 0) {
    notificationStore.setGeneralServiceNotification('Gaps Present', 'Please fill gaps before creating the playlist.')
    return
  }
  if (store.scheduleItems.length === 0) {
    notificationStore.setGeneralServiceNotification('No Schedule Items', 'Please add schedule items before creating the playlist.')
    return
  }

  await store.createPlaylist({
    name: name.value,
    description: description.value,
    url: url.value,
    type: type.value,
    status: status.value,
    start_dateTime: store.startTimeUTC,
    end_dateTime: store.endTimeUTC,
    priority: priority.value,
    repeat_mode: repeat_mode.value,
    next_playlist_id: repeat_mode.value === 'next_playlist' ? next_playlist_id.value : null,
  })
  if (!store.error) {
    name.value = ''
    description.value = ''
    url.value = ''
    type.value = 'regular'
    status.value = 'active'
    store.startTime = dayjs().format('YYYY-MM-DDTHH:mm')
    store.endTime = dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm')
    priority.value = 1
    repeat_mode.value = 'repeat_all'
    next_playlist_id.value = null
  } else {
    error.value = store.error
  }
}

const useSchedule = async () => {
  if (!store.startTimeUTC || !store.endTimeUTC) {
    notificationStore.setGeneralServiceNotification('Date Time Required', 'Please select both start and end date times.')
    return
  }

  await store.fetchSchedules(store.startTimeUTC, store.endTimeUTC)
}

const clearStartDateTime = () => {
  store.startTime = ''
}

const setStartDateTimeNow = () => {
  store.startTime = dayjs().format('YYYY-MM-DDTHH:mm')
}

const clearEndDateTime = () => {
  store.endTime = ''
}

const setEndDateTimeNow = () => {
  store.endTime = dayjs().format('YYYY-MM-DDTHH:mm')
}

// Method to remove item from the store
const removeItem = (id) => {
  store.removeItem(id)
}

// Method to add item back to the store
const addItem = (id) => {
  store.addItem(id)
}

// Method to clear all removed items from the store
const clearRemovedItems = () => {
  store.clearRemovedItems()
}

// Method to resolve conflicts
const resolveConflicts = () => {
  store.resolveConflicts()
}

const openAddContentModal = (gapItem) => {
  selectedGapDuration.value = gapItem.duration_minutes
  showModal.value = true
  startDateTime.value = gapItem.start_dateTime
}

const closeModal = () => {
  showModal.value = false
}

const fetchPlaylistsIfNeeded = async () => {
  console.log('fetching playlists...')
  if (repeat_mode.value === 'next_playlist' && store.playlists.length === 0) {
    await store.fetchPlaylists()
  }
}

// Watch the repeat_mode variable and call fetchPlaylistsIfNeeded when it changes
watch(repeat_mode, (newValue) => {
  if (newValue === 'next_playlist') {
    fetchPlaylistsIfNeeded()
  }
})
</script>
