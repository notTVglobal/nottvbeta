<template>
  <div class="px-4 pt-4 bg-white rounded-lg shadow-lg relative flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Edit Playlist</h2>

    <div class="flex-grow overflow-auto pr-2">
      <form @submit.prevent="updatePlaylist" class="space-y-4">
        <PlaylistForm />
        <ScheduleItems submitButtonText="Update" />
      </form>
    </div>
  </div>
</template>

<script setup>
import { watch } from 'vue'
import { usePlaylistForm } from '@/Mixins/usePlaylistForm'
import PlaylistForm from '@/Components/Pages/Admin/ChannelPlaylists/PlaylistForm.vue'
import ScheduleItems from '@/Components/Pages/Admin/ChannelPlaylists/ScheduleItems.vue'

const props = defineProps({
  playlist: Object,
})

const {
  store,
  name,
  description,
  url,
  type,
  priority,
  repeat_mode,
  next_playlist_id,
  error,
  selectedGapDuration,
  showModal,
  startDateTime,
  fetchPlaylistsIfNeeded,
  validateTimes,
} = usePlaylistForm()

watch(
    () => props.playlist,
    (newPlaylist) => {
      if (newPlaylist) {
        name.value = newPlaylist.name
        description.value = newPlaylist.description
        url.value = newPlaylist.url
        type.value = newPlaylist.type
        priority.value = newPlaylist.priority
        repeat_mode.value = newPlaylist.repeat_mode
        next_playlist_id.value = newPlaylist.next_playlist_id
        store.startTime = newPlaylist.start_dateTime
        store.endTime = newPlaylist.end_dateTime
      }
    },
    { immediate: true }
)

const updatePlaylist = async () => {
  // Validate the form times using the mixin logic
  if (!validateTimes()) {
    return
  }

  await store.updatePlaylist(props.playlist.id, {
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
    // Additional success logic
  } else {
    error.value = store.error
  }
}
</script>
