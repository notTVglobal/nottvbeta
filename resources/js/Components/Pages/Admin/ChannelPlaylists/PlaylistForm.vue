<template>
  <div>
    <div>
      <label for="name" class="block font-medium text-gray-700">Name:</label>
      <input type="text" v-model="store.name" id="name" class="input input-bordered w-full mt-1" required>
    </div>
    <div>
      <label for="description" class="block font-medium text-gray-700">Description:</label>
      <textarea v-model="store.description" id="description" class="input input-bordered w-full mt-1"></textarea>
    </div>
    <div>
      <label for="url" class="block font-medium text-gray-700">URL:</label>
      <input type="text" v-model="store.url" id="url" class="input input-bordered w-full mt-1">
    </div>
    <div>
      <label for="type" class="block font-medium text-gray-700">Type:</label>
      <select v-model="store.type" id="type" class="input input-bordered w-full mt-1">
        <option value="regular">Regular</option>
        <option value="event">Event</option>
        <option value="special">Special</option>
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
      <input type="number" v-model="store.priority" id="priority" class="input input-bordered w-full mt-1">
    </div>
    <div>
      <label for="repeat_mode" class="block font-medium text-gray-700">Repeat Mode: <span
          class="text-sm text-gray-500">(What happens when the playlist reaches the end?)</span></label>
      <select v-model="store.repeat_mode" id="repeat_mode" class="input input-bordered w-full mt-1">
        <option value="repeat_all">Repeat All</option>
        <option value="repeat_last">Repeat Last One Only</option>
        <option value="shuffle">Shuffle</option>
        <option value="stop">Stop</option>
        <option value="next_playlist">Play A Different Playlist</option>
      </select>
    </div>
    <div v-if="store.repeat_mode === 'next_playlist'">
      <label for="next_playlist" class="block font-medium text-gray-700">Select Playlist:</label>
      <select v-model="store.next_playlist_id" id="next_playlist" class="input input-bordered w-full mt-1">
        <option v-for="playlist in store.playlists" :key="playlist.id" :value="playlist.id">
          {{ playlist.name }}
        </option>
      </select>
    </div>
  </div>
</template>

<script setup>
import { usePlaylistForm } from '@/Mixins/usePlaylistForm'
import { watch } from 'vue'

// Use the mixin to get the shared form logic
const {
  store,
  clearStartDateTime,
  setStartDateTimeNow,
  clearEndDateTime,
  setEndDateTimeNow,
  fetchPlaylistsIfNeeded
} = usePlaylistForm()

// Watch the repeat_mode variable and call fetchPlaylistsIfNeeded when it changes
watch(() => store.repeat_mode, (newValue) => {
  console.log('next_playlist selected')
  if (newValue === 'next_playlist') {
    fetchPlaylistsIfNeeded()
  }
}, {immediate: true})
</script>
