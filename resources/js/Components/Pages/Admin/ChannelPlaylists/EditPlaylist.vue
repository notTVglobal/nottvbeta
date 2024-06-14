<template>
  <div class="px-4 pt-4 bg-white rounded-lg shadow-lg relative flex flex-col h-full">

    <div class="flex-grow overflow-auto pr-2">
      <form @submit.prevent="updatePlaylist" class="space-y-4">
        <PlaylistForm/>
        <ScheduleItems submitButtonText="Update" @openAddContentModal="handleOpenAddContentModal"/>
      </form>
      <AddContentModal
          v-if="store.showModal"
          :showModal="store.showModal"
          :gapDuration="selectedGapDuration"
          :startDateTime="startDateTime"
      />
    </div>
  </div>
</template>

<script setup>
import { onUnmounted, watch } from 'vue'
import { usePlaylistForm } from '@/Mixins/usePlaylistForm'
import PlaylistForm from '@/Components/Pages/Admin/ChannelPlaylists/PlaylistForm.vue'
import ScheduleItems from '@/Components/Pages/Admin/ChannelPlaylists/ScheduleItems.vue'
import AddContentModal from '@/Components/Pages/Admin/ChannelPlaylists/AddContentModal.vue'

const props = defineProps({
  playlist: Object,
})

const {
  store,
  error,
  selectedGapDuration,
  showModal,
  startDateTime,
  fetchPlaylistsIfNeeded,
  validateTimes,
  openAddContentModal,
} = usePlaylistForm()

// watch(
//     () => props.playlist,
//     (newPlaylist) => {
//       console.log('Received new playlist:', newPlaylist)
//       if (newPlaylist) {
//         store.setPlaylistData(newPlaylist)
//       } else {
//         console.error('Received undefined or null playlist:', newPlaylist)
//       }
//     },
//     {immediate: true},
// )

const updatePlaylist = async () => {
  // Validate the form times using the mixin logic
  if (!validateTimes()) {
    return
  }

  const playlistData = {
    id: props.playlist.id,
    name: store.name,
    description: store.description,
    url: store.url,
    type: store.type,
    start_dateTime: store.startTimeUTC,
    end_dateTime: store.endTimeUTC,
    priority: store.priority,
    repeat_mode: store.repeat_mode,
    next_playlist_id: store.repeat_mode === 'next_playlist' ? store.next_playlist_id : null,
    scheduleItems: store.getValidScheduleItems(), // Assuming getValidScheduleItems() is a method that returns the valid schedule items.
  }

  await store.updatePlaylist(playlistData)

  if (!store.error) {
    // Additional success logic
  } else {
    console.error('Error:', store.error)
  }
}

const handleOpenAddContentModal = (gapItem) => {
  openAddContentModal(gapItem)
  console.log('openAddContentModal', gapItem)
}

// Watch the repeat_mode variable and call fetchPlaylistsIfNeeded when it changes
watch(
    () => store.repeat_mode,
    (newValue) => {
      console.log('next_playlist selected')
      if (newValue === 'next_playlist') {
        store.fetchPlaylists()
      }
    },
    {immediate: true},
)

</script>
