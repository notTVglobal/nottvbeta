<template>
  <Head title="Admin/Channels"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader
          :displayBadges="true"
          :badgePrimaryNumber="adminStore.activeChannelsCount"
          :badgeSecondaryNumber="adminStore.channels.length">Channels
      </AdminHeader>
      <AdminChannelHeaderButtons/>

      <div class="bg-orange-300 px-2 text-black mb-3">
        <p>Add a channel: create playlist and add shows.</p>
        <p><span class="font-semibold">TRAVIS NOTE: </span>Use FFMPEG to push an .mp4 to a mist stream. This will allow
          us to schedule video objects from anywhere
          into our channels, and we can have a channel that loads the current video at the right time spot in the video
          based on our schedule saved in the database.</p>
      </div>

      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

              <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div class="table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                  <div class="table-header-group text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <AdminChannelRowHeader @reloadChannelsList="reloadChannelsList"/>
                  </div>
                  <div class="table-row-group">
                    <AdminChannelRow
                        v-for="channel in adminStore.paginatedChannels"
                        :key="channel.id"
                        :channel="channel"
                        @open-modal="openModal"/>
                  </div>
                </div>

                <!-- Paginator -->
                <AdminChannelPaginator/>

              </div>
              <DynamicModal/>


            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useAdminStore } from '@/Stores/AdminStore'
import AdminHeader from '@/Components/Pages/Admin/AdminHeader'
import Message from '@/Components/Global/Modals/Messages'
import DynamicModal from '@/Components/Pages/Admin/Channels/DynamicModal'
import AdminChannelHeaderButtons from '@/Components/Pages/Admin/Channels/AdminChannelHeaderButtons'
import AdminChannelRow from '@/Components/Pages/Admin/Channels/AdminChannelRow.vue'
import AdminChannelPaginator from '@/Components/Pages/Admin/Channels/AdminChannelPaginator.vue'
import AdminChannelRowHeader from '@/Components/Pages/Admin/Channels/AdminChannelRowHeader.vue'

usePageSetup('admin.channels')

const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()
const adminStore = useAdminStore()

adminStore.fetchChannels()

function hasChannelSource(channel) {
  if (channel && channel.source && channel.source.name) {
    return channel.source.name
  }
  return null // Or return any other default value if needed
}

// let search = ref(props.filters.search);

// watch(search, throttle(function (value) {
//   router.get('/admin/channels', {search: value}, {
//     preserveState: true,
//     replace: true
//   });
// }, 300));

const hasPriority = (playbackPriorityType) => {
  if (playbackPriorityType) {
    return true
  }
}

const openModal = ({channel, type}) => {
  // adminStore.reset()
  // adminStore.fetchChannels()
  adminStore.setSelectedChannel(channel)
  adminStore.setCurrentType(type)
  adminStore.fetchItems(type) // Optionally prefetch items if the modal needs it
  document.getElementById('dynamicModal').showModal() // Assuming the modal has an ID of 'dynamicModal'
}

const setPlaybackPriorityType = async (channel, priorityType) => {
  const dataToSend = {setPriorityType: priorityType}
  try {
    const response = await axios.post(`/admin/channels/${channel.id}/setPlaybackPriorityType`, dataToSend)
    // do something here
  } catch (error) {
    console.log(error)
  }
}

const reloadChannelsList = async () => {
  try {
    await adminStore.fetchChannels()
    // On success, show a success toast
    notificationStore.setToastNotification('Channels reloaded successfully!', 'success', 3000)
  } catch (error) {
    // On failure, show an error toast
    console.error(error)
    notificationStore.setToastNotification('Failed to reload channels.', 'error', 3000)

  }
}
</script>
