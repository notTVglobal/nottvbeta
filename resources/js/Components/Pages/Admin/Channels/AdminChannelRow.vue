<template>
  <div class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <div class="table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
      <span class="text-xl">{{ channel.name }}</span>
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

  </div>
</template>

<script setup>
import { useAdminStore } from '@/Stores/AdminStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import AdminChannelDataItem from '@/Components/Pages/Admin/Channels/AdminChannelDataItem'
import AdminChannelCurrentVideo from '@/Components/Pages/Admin/Channels/AdminChannelCurrentVideo.vue'

const adminStore = useAdminStore()
const notificationStore = useNotificationStore()

const props = defineProps({
  channel: Object,
})

const emit = defineEmits(['open-modal'])

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
