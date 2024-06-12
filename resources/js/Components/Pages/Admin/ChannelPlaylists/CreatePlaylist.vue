<template>
  <div class="px-4 pt-4 bg-white rounded-lg shadow-lg relative flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Create New Playlist</h2>

    <div class="flex-grow overflow-auto pr-2">
      <form @submit.prevent="createPlaylist" class="space-y-4">
        <PlaylistForm />
        <ScheduleItems submitButtonText="Create" />
      </form>
    </div>
    <AddContentModal
        v-if="store.showAddContentModal"
        :showModal="store.showAddContentModal"
        :gapDuration="selectedGapDuration"
        :startDateTime="startDateTime"
    />
  </div>
</template>

<script setup>
import { watch } from 'vue'
import { usePlaylistForm } from '@/Mixins/usePlaylistForm'
import AddContentModal from '@/Components/Pages/Admin/ChannelPlaylists/AddContentModal.vue'
import PlaylistForm from '@/Components/Pages/Admin/ChannelPlaylists/PlaylistForm.vue'
import ScheduleItems from '@/Components/Pages/Admin/ChannelPlaylists/ScheduleItems.vue'

// Use the mixin to get the shared form logic
const {
  store,
  notificationStore,
  error,
  selectedGapDuration,
  showModal,
  startDateTime,
  fetchPlaylistsIfNeeded,
  validateTimes,
} = usePlaylistForm()

const createPlaylist = async () => {
  // Validate the form times using the mixin logic
  if (!validateTimes()) {
    return
  }

  await store.createPlaylist({
    name: name.value,
    description: description.value,
    url: url.value,
    type: type.value,
    start_dateTime: store.startTimeUTC,
    end_dateTime: store.endTimeUTC,
    priority: priority.value,
    repeat_mode: repeat_mode.value,
    next_playlist_id: repeat_mode.value === 'next_playlist' ? next_playlist_id.value : null,
  })

  if (!store.error) {
    // Reset the form fields after successful creation
    name.value = ''
    description.value = ''
    url.value = ''
    type.value = 'regular'
    store.startTime = dayjs().format('YYYY-MM-DDTHH:mm')
    store.endTime = dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm')
    priority.value = 1
    repeat_mode.value = 'repeat_all'
    next_playlist_id.value = null

    // Close modal or handle success
    document.getElementById('addChannelPlaylistModal').close()
  } else {
    error.value = store.error
  }
}

</script>
