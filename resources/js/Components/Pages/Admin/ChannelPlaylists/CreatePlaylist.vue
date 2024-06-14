<template>
  <div class="px-4 pt-4 bg-white rounded-lg shadow-lg relative flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Create New Playlist</h2>

    <div class="flex-grow overflow-auto pr-2">
      <form @submit.prevent="createPlaylist" class="space-y-4">
        <PlaylistForm />
        <ScheduleItems submitButtonText="Create" @openAddContentModal="handleOpenAddContentModal" />
      </form>
    </div>
    <AddContentModal
        v-if="store.showModal"
        :showModal="store.showModal"
        :gapDuration="selectedGapDuration"
        :startDateTime="startDateTime"
    />
  </div>
</template>

<script setup>
import { usePlaylistForm } from '@/Mixins/usePlaylistForm'
import AddContentModal from '@/Components/Pages/Admin/ChannelPlaylists/AddContentModal.vue'
import PlaylistForm from '@/Components/Pages/Admin/ChannelPlaylists/PlaylistForm.vue'
import ScheduleItems from '@/Components/Pages/Admin/ChannelPlaylists/ScheduleItems.vue'
import dayjs from 'dayjs'

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
  openAddContentModal,
  closeModal,
} = usePlaylistForm()

const createPlaylist = async () => {
  // Validate the form times using the mixin logic
  if (!validateTimes()) {
    return
  }

  await store.createPlaylist({
    name: store.name,
    description: store.description,
    url: store.url,
    type: store.type,
    start_dateTime: store.startTimeUTC,
    end_dateTime: store.endTimeUTC,
    priority: store.priority,
    repeat_mode: store.repeat_mode,
    next_playlist_id: store.repeat_mode === 'next_playlist' ? store.next_playlist_id : null,
  })

  if (!store.error) {
    // Reset the form fields after successful creation
    store.name = ''
    store.description = ''
    store.url = ''
    store.type = 'regular'
    store.startTime = dayjs().format('YYYY-MM-DDTHH:mm')
    store.endTime = dayjs().add(24, 'hour').format('YYYY-MM-DDTHH:mm')
    store.priority = 1
    store.repeat_mode = 'repeat_all'
    store.next_playlist_id = null

    // Close modal or handle success
    document.getElementById('addChannelPlaylistModal').close()
    store.reset()
  } else {
    error.value = store.error
  }
}

const handleOpenAddContentModal = (gapItem) => {
  openAddContentModal(gapItem);
  console.log('openAddContentModal', gapItem);
}
</script>
