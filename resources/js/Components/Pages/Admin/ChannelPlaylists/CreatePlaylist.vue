<template>
  <div class="p-4 bg-white rounded-lg shadow-lg relative flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Create New Playlist</h2>
    <div class="flex-grow overflow-auto mb-4">
    <form @submit.prevent="createPlaylist" class="space-y-4">
      <div>
        <label for="name" class="block font-medium text-gray-700">Name:</label>
        <input type="text" v-model="name" id="name" class="input input-bordered w-full mt-1" required>
      </div>
      <div>
        <label for="description" class="block font-medium text-gray-700">Description:</label>
        <textarea v-model="description" id="description" class="input input-bordered w-full mt-1" required></textarea>
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
          <input type="datetime-local" v-model="store.startTimeUTC" id="start_dateTime" class="input input-bordered w-full mt-1">
          <button type="button" @click="clearStartDateTime" class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Clear</button>
          <button type="button" @click="setStartDateTimeNow" class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Now</button>
        </div>
      </div>
      <div>
        <label for="end_dateTime" class="block font-medium text-gray-700">
          End DateTime: <span class="text-sm text-gray-500">(optional)</span>
        </label>
        <div class="flex">
          <input type="datetime-local" v-model="store.endTimeUTC" id="end_dateTime" class="input input-bordered w-full mt-1">
          <button type="button" @click="clearEndDateTime" class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Clear</button>
          <button type="button" @click="setEndDateTimeNow" class="btn bg-gray-300 hover:bg-gray-400 text-black ml-2 mt-1">Now</button>
        </div>
      </div>
      <div>
        <label for="priority" class="block font-medium text-gray-700">Priority:</label>
        <input type="number" v-model="priority" id="priority" class="input input-bordered w-full mt-1">
      </div>
      <div>
        <label for="repeat_mode" class="block font-medium text-gray-700">Repeat Mode: <span class="text-sm text-gray-500">(What happens when the playlist reaches the end?)</span></label>
        <select v-model="repeat_mode" id="repeat_mode" class="input input-bordered w-full mt-1">
          <option value="repeat_all">Repeat All</option>
          <option value="repeat_last">Repeat Last One Only</option>
          <option value="shuffle">Shuffle</option>
          <option value="stop">Stop</option>
          <option value="play_different_playlist">Play A Different Playlist</option>
        </select>
      </div>
      <div v-if="repeat_mode === 'play_different_playlist'">
        <label for="next_playlist" class="block font-medium text-gray-700">Select Playlist:</label>
        <select v-model="next_playlist_id" id="next_playlist" class="input input-bordered w-full mt-1">
          <option v-for="playlist in store.playlists" :key="playlist.id" :value="playlist.id">
            {{ playlist.name }}
          </option>
        </select>
      </div>
      <div class="flex justify-between">
        <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Create</button>
        <button type="button" @click="useSchedule"
                class="btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          <span v-if="store.loadingSchedules" class="loading loading-spinner"/>
          <span v-else>Use Schedule</span>
        </button>
      </div>
    </form>
    <div v-if="store.scheduleItemsWithUserTimezone.length" class="mt-6">
      <h3 class="text-lg font-bold mb-2">Schedule Items</h3>
      <div class="mb-4" v-if="store.hasRemovedItems">
        <button @click="clearRemovedItems" class="btn btn-sm btn-danger">Clear All Removed Items</button>
      </div>
      <ul :class="isSmallScreen ? 'space-y-4' : 'divide-y divide-gray-200 space-y-4'">
        <li
            v-for="(item, index) in store.scheduleItemsWithUserTimezone"
            :key="item.id"
            :class="getListItemClass(item.conflict, item.removed)"
        >
          <div :class="isSmallScreen ? '' : 'flex flex-wrap items-start w-full'">
            <div v-if="!item.removed" class="flex items-center space-x-2 mb-2">
              <SingleImage :image="item.content.image" :class="`w-10 h-10 object-contain`" />
              <div>
                <div class="text-sm font-semibold">{{ item.content.name }}</div>
                <div class="text-xs text-gray-500">{{ item.duration_minutes }} minutes &middot; Priority: {{ item.priority }}</div>
              </div>
            </div>
            <div v-if="!item.removed" class="flex items-center space-x-2 mb-2 w-full">
              <SingleImage :image="item.content.team.image" :class="`w-5 h-5 object-contain`" />
              <div class="text-sm">{{ item.content.team.name }}</div>
            </div>
            <div class="text-sm mb-2 w-full">
              <div><span class="text-xs uppercase font-semibold">Start time: </span>{{ formatDateTime(item.start_dateTime) }}</div>
              <div v-if="!item.removed"><span class="text-xs uppercase font-semibold">End time: </span>{{ formatDateTime(item.end_date_time) }}</div>
              <div v-if="item.removed" class="flex items-center space-x-2">
                <div class="text-sm font-semibold">{{ item.content.name }}</div>
                <div class="text-xs text-gray-500">{{ item.duration_minutes }} minutes</div>
              </div>
            </div>
            <div class="w-full mt-2 flex justify-between items-center">
              <span class="badge badge-outline" :class="getBadgeClass(item.type)">{{ item.type }}</span>
              <div v-if="!item.removed && item.conflict">
                <button :key="'remove-' + item.id" @click="removeItem(item.id)" class="btn btn-sm btn-danger">Remove</button>
              </div>
              <div v-else-if="item.removed">
                <button :key="'add-' + item.id" @click="addItem(item.id)" class="btn btn-sm btn-success">Add</button>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <p v-if="error" class="mt-4 text-red-500">{{ error }}</p>
    </div>
    <div v-if="store.scheduleItemsWithUserTimezone.length && (store.conflictCount > 0 || store.gapCount > 0)" class="fixed bottom-0 left-0 w-full p-4 bg-white border-t border-gray-200">
      <span class="text-sm text-red-600 font-semibold">Conflicts: {{ store.conflictCount }}</span>
      <span class="text-sm text-orange-600 font-semibold ml-4">Gaps: {{ store.gapCount }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import { useChannelPlaylistStore } from '@/Stores/ChannelPlaylistStore'
import { useNotificationStore } from '@/Stores/NotificationStore';
import { useUserStore } from '@/Stores/UserStore';
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

dayjs.extend(utc);

const store = useChannelPlaylistStore()
const notificationStore = useNotificationStore();
const userStore = useUserStore();

const isSmallScreen = computed(() => userStore.isSmallScreen);

const getListItemClass = (conflict, removed) => {
  if (removed) {
    return 'flex flex-col p-2 border-b border-gray-200 space-y-2 bg-gray-100 opacity-50';
  }
  return isSmallScreen.value
      ? 'flex flex-col p-2 border-b border-gray-200 space-y-2'
      : `p-4 space-y-2 ${conflict ? 'bg-red-100' : ''}`;
};

const name = ref('')
const description = ref('')
const url = ref('')
const type = ref('regular')
const status = ref('active')
// const start_dateTime = ref(dayjs().format('YYYY-MM-DDTHH:mm'));
// const end_dateTime = ref(dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm'));
const priority = ref(0)
const repeat_mode = ref('repeat_all')
const next_playlist_id = ref(null);
const scheduleItems = ref([])
const error = ref(null)

const createPlaylist = async () => {
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
    next_playlist_id: repeat_mode.value === 'play_different_playlist' ? next_playlist_id.value : null,
  })
  if (!store.error) {
    name.value = ''
    description.value = ''
    url.value = ''
    type.value = 'regular'
    status.value = 'active'
    store.startTimeUTC = dayjs().format('YYYY-MM-DDTHH:mm');
    store.endTimeUTC = dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm');
    priority.value = 0
    repeat_mode.value = 'repeat_all'
    next_playlist_id.value = null
  } else {
    error.value = store.error
  }
}

const useSchedule = async () => {
  if (!store.startTimeUTC || !store.endTimeUTC) {
    notificationStore.setGeneralServiceNotification('Date Time Required', 'Please select both start and end date times.');
    return;
  }

  // const startTimeUTC = dayjs(store.startTimeUTC).utc().format();
  // const endTimeUTC = dayjs(store.endTimeUTC).utc().format();

  await store.fetchSchedules(store.startTimeUTC, store.endTimeUTC);
};

const clearStartDateTime = () => {
  store.startTimeUTC = '';
};

const setStartDateTimeNow = () => {
  store.endTimeUTC = dayjs().format('YYYY-MM-DDTHH:mm');
};

const clearEndDateTime = () => {
  store.startTimeUTC = '';
};

const setEndDateTimeNow = () => {
  store.endTimeUTC = dayjs().format('YYYY-MM-DDTHH:mm');
};

// Method to remove item from the store
const removeItem = (id) => {
  console.log('Removing item with id:', id);
  store.removeItem(id);
};

// Method to add item back to the store
const addItem = (id) => {
  console.log('Adding item with id:', id);
  store.addItem(id);
};

// Method to clear all removed items from the store
const clearRemovedItems = () => {
  console.log('Clearing all removed items');
  store.clearRemovedItems();
};

const fetchPlaylistsIfNeeded = async () => {
  if (repeat_mode.value === 'play_different_playlist' && store.playlists.length === 0) {
    await store.fetchPlaylists();
  }
};

// Function to get badge class based on type
const getBadgeClass = (type) => {
  switch(type) {
    case 'movie':
      return 'text-red-500';
    case 'show':
      return 'text-blue-500';
    case 'showEpisode':
      return 'text-green-500';
    case 'otherContent':
      return 'text-purple-500';
    case 'newsStory':
      return 'text-yellow-500';
    default:
      return 'text-gray-500';
  }
};
</script>

