<template>
  <div class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <div class="table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
      <button @click.prevent="openEditChannelModal" class="text-xl">{{ channel.name }}</button>
      <div v-if="channel?.isLive" class="ml-2 text-xs badge badge-secondary badge-outline">live</div>
    </div>

    <!-- Current Video -->
    <div class="md:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap text-center">
      <AdminChannelCurrentVideo :data="channel"/>
    </div>

    <!-- ExternalSource -->
    <div class="table-cell px-6 py-4 align-middle text-center">
      <AdminChannelDataItem
          :data="channel"
          :dataType="`externalSource`"
          :dataTypeDb="`external_source`"
          @open-modal="handleOpenModal('externalSource')"/>
    </div>

    <!-- ChannelPlaylist -->
    <div class="table-cell px-6 py-4 align-middle text-center">
      <AdminChannelDataItem
          :data="channel"
          :dataType="`channelPlaylist`"
          :dataTypeDb="`channel_playlist`"
          @open-modal="handleOpenModal('channelPlaylist')"/>
    </div>

    <!-- MistStream -->
    <div class="table-cell px-6 py-4 align-middle text-center">
      <AdminChannelDataItem
          :data="channel"
          :dataType="`mistStream`"
          :dataTypeDb="`mist_stream`"
          @open-modal="handleOpenModal('mistStream')"/>
    </div>

    <!-- Active Toggle -->
    <div class="table-cell px-6 py-4">
      <div class="flex justify-center items-center w-full">
        <label class="cursor-pointer label">
          <input v-model="channel.active"
                 type="checkbox"
                 @click.prevent="toggleChannelActiveStatus"
                 class="toggle toggle-primary"/>
        </label>
      </div>
    </div>

    <dialog :id="'adminEditChannel_'+channel.id" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-2">Edit Channel Name</h3>
        <input v-model="channelName" type="text" class="input input-bordered w-full max-w-xs" />
        <button @click.prevent="submit" class="btn btn-primary ml-2">Submit</button>
      </div>
    </dialog>

  </div>
</template>

<script setup>
import { useAdminStore } from '@/Stores/AdminStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import AdminChannelDataItem from '@/Components/Pages/Admin/Channels/AdminChannelDataItem'
import AdminChannelCurrentVideo from '@/Components/Pages/Admin/Channels/AdminChannelCurrentVideo.vue'
import { computed, ref } from 'vue'

const adminStore = useAdminStore()
const notificationStore = useNotificationStore()

const props = defineProps({
  channel: Object,
})

const emit = defineEmits(['open-modal'])

const channelName = ref(props.channel.name)

const openEditChannelModal = () => {
  document.getElementById('adminEditChannel_'+props.channel.id).showModal()
}


const submit = async () => {
  await adminStore.updateChannel(props.channel.id, channelName.value)
  document.getElementById('adminEditChannel_'+props.channel.id).close()
}

// Handle the open-modal event from grandchildren and re-emit to parent
const handleOpenModal = (type) => {
  // You can add more logic here if needed before emitting
  emit('open-modal', {channel: props.channel, type})
}

const toggleChannelActiveStatus = async (event) => {
  try {
    await adminStore.toggleChannelActiveStatus(props.channel.id);
    await adminStore.fetchChannels();
  } catch (error) {
    console.error('Error toggling channel active status:', error);
    // Assuming notificationStore is properly imported and instantiated
    notificationStore.setToastNotification('Error toggling channel active status: ' + error, 'error');
  } finally {
    // Blurring the target element directly
    if (event && event.target) {
      event.target.blur();
    }
  }
}

</script>
