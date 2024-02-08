<template>
  <Head title="Admin/Channels"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Channels</AdminHeader>

      <div class="flex flex-row justify-between gap-x-4 mb-3">
        <div>
          <Link :href="`#`">
            <button
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
                disabled
            >Add Channel
            </button>
          </Link>
          <Link :href="`#`">
            <button
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
                disabled
            >Add External Source
            </button>
          </Link>
          <Link :href="`#`">
            <button
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
                disabled
            >Add Channel Playlist
            </button>
          </Link>
          <AdminAddMistStreamModal />

        </div>
        <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg"/>
      </div>

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
                <div
                    class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
                >
                  <div
                      class="table-header-group text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                  >
                    <div class="table-row">
                      <div scope="col" class="table-cell px-6 py-3 uppercase">
                        <font-awesome-icon icon="fa-repeat" class="mr-2 cursor-pointer hover:text-blue-500"
                                           @click.prevent="reload()"/>
                        <span class="uppercase">Channel </span>
                      </div>
                      <div scope="col" class="hidden md:table-cell px-6 py-3 uppercase">
                        <span class="uppercase">Current Video</span>
                      </div>
                      <div scope="col" class="table-cell px-6 py-3 uppercase">
                        <span class="uppercase">External Source</span> <br/>
                        <span class="italic text-xs text-gray-400"></span>
                      </div>
                      <div scope="col" class="px-6 py-3 max-w-64">
                        <span class="uppercase">Channel Playlist</span> <br />
                        <span class="italic text-xs text-gray-400">Knows the currently playing object to feed our NowPlayingInfo panel. Push the object to the set MistStream via ffmpeg realtime video playout. Will need to make it through a validateUser trigger. This will allow the MistServer receiving the streams to Push out to the actual playback destinations... e.g., a closer MistServer to the user on Kubernetes to scale with a load balancer as needed, or to LivePeer, or to Cloudflare, or to PeerTube.</span>
                      </div>
                      <div scope="col" class="table-cell px-6 py-3">
                        <span class="uppercase">Mist Stream</span> <br/>
                        <span class="italic text-xs text-gray-400"></span>
                      </div>

                    </div>
                  </div>
                  <div class="table-row-group">
                    <div
                        v-for="channel in channels.data"
                        :key="channel.id"
                        class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                      <div
                          scope="row"
                          class="table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                      >
                        <span>{{ channel.name }}</span>
                        <div v-if="channel?.isLive" class="ml-2 text-xs badge badge-secondary badge-outline">live</div>

                      </div>
                      <div
                          scope="row"
                          class="md:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                      >
                        <div v-if="channel?.currentVideo">
                          <Popper
                              hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0"
                              placement="bottom"

                          >
                            <template #content>
                              <div class="text-xs" id="tooltip">
                                <div>ID: {{ channel?.currentVideo.id }}</div>
                                <div>File Name: {{ channel?.currentVideo.file_name }}</div>
                                <div>Type: {{ channel?.currentVideo.type }}</div>
                                <div>Folder: {{ channel?.currentVideo.folder }}</div>
                                <div>Storage Location: {{ channel?.currentVideo.storage_location }}</div>
                                <div>Processing: <span v-if="channel?.currentVideo.is_processing">true</span><span
                                    v-else>false</span></div>
                                <div>Length: {{ channel?.currentVideo.length }}</div>
                              </div>
                            </template>

                            <span v-if="channel?.currentVideo.name" class="cursor-help text-green-500 font-semibold">{{
                                channel?.currentVideo.name
                              }}</span>
                            <span v-else class="cursor-help text-blue-500 font-semibold">Video has no name</span>

                          </Popper>

                        </div>
                        <div v-else class="italic text-gray-300 text-xs">No video object playing</div>
                      </div>
                      <div class="table-cell px-6 py-4">
                        <div v-if="channel?.externalSource">
                          <Popper
                              hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0"
                              placement="bottom"

                          >
                            <template #content>
                              <div class="text-xs" id="tooltip">
                                <div>ID: {{ channel?.externalSource.id }}</div>
                                <div>Name: {{ channel?.externalSource.name }}</div>
                                <div>Path: {{ channel?.externalSource.path }}</div>
                                <div>Type: {{ channel?.externalSource.type }}</div>
                                <div>Provider: {{ channel?.externalSource.provider }}</div>
                              </div>
                            </template>

                            <span class="cursor-help text-orange-500 font-semibold">{{
                                hasChannelSource(channel)
                              }}</span>

                          </Popper>


                        </div>
                        <div v-else>
                          <button @click="openModal(channel, 'externalSource')">
                            <span v-if="channel?.externalSource" class="text-green-500 font-semibold">{{ channel?.externalSource }}</span>
                            <span v-else class="italic text-gray-300 text-sm">no source</span>
                          </button>
                          <!-- Status indicator + action to change source -->
                          <source-selector :priority="channel.playbackPriorityType" :source="channel.externalSource" @change="updateSource(channel.id, $event, 'external')"></source-selector>

                        </div>

                      </div>
                      <div class="table-cell px-6 py-4">
                        <div v-if="channel?.channelPlaylist">
                          <Popper
                              hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0"
                              placement="bottom"

                          >
                            <template #content>
                              <div class="text-xs" id="tooltip">
                                <div>ID: {{ channel?.channelPlaylist.id }}</div>
                                <div>Name: {{ channel?.channelPlaylist.name }}</div>
                                <div>Path: {{ channel?.channelPlaylist.path }}</div>
                                <div>Type: {{ channel?.channelPlaylist.type }}</div>
                                <div>Provider: {{ channel?.channelPlaylist.provider }}</div>
                              </div>
                            </template>

                            <span class="cursor-help text-orange-500 font-semibold"></span>

                          </Popper>

                        </div>
                        <div v-else>
                          <button @click="openModal(channel, 'channelPlaylist')">
                            <span v-if="channel?.channelPlaylist" class="text-green-500 font-semibold">{{ channel?.channelPlaylist }}</span>
                            <span v-else class="italic text-gray-300 text-sm">no playlist</span>
                          </button>
                          <source-selector :priority="channel?.playbackPriorityType" :source="channel?.channelPlaylist" @change="updateSource(channel.id, $event, 'playlist')"></source-selector>

                        </div>

                      </div>
                      <div class="table-cell px-6 py-4">
                        <button @click="openModal(channel, 'mistStream')">
                          <span v-if="channel?.mistStream" class="text-green-500 font-semibold">{{ channel?.mistStream }}</span>
                          <span v-else class="italic text-gray-300 text-sm">no mist stream</span>
                        </button>
                        <!-- Status indicator + action to change source -->
                        <source-selector :source="channel?.mistStream" @change="updateSource(channel.id, $event, 'mist')"></source-selector>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- Paginator -->
                <Pagination :data="channels" class="pb-6"/>
              </div>
              <DynamicModal />

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>


<script setup>
import { Inertia } from "@inertiajs/inertia";
import { ref, watch } from "vue"
import throttle from "lodash/throttle"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useAdminStore } from '@/Stores/AdminStore'
import AdminHeader from "@/Components/Pages/Admin/AdminHeader"
import Message from "@/Components/Global/Modals/Messages"
import Pagination from "@/Components/Global/Paginators/Pagination"
import SourceSelector from '@/Components/Pages/Admin/Channels/AdminChannelSourceSelector'
import DynamicModal from '@/Components/Pages/Admin/Channels/DynamicModal.vue'
import AdminAddMistStreamModal from '@/Components/Pages/Admin/Channels/AdminAddMistStreamModal.vue'

usePageSetup('admin.channels')

const appSettingStore = useAppSettingStore()
const adminStore = useAdminStore()

let props = defineProps({
  channels: Object,
  mistStreams: Object,
  filters: Object,
})

// function hasChannelSource (channel) {
//     if (channel.source !== null) {
//         return channel.source.name
//     }
// }

function hasChannelSource(channel) {
  if (channel && channel.source && channel.source.name) {
    return channel.source.name;
  }
  return null; // Or return any other default value if needed
}

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
  Inertia.get('/admin/channels', {search: value}, {
    preserveState: true,
    replace: true
  });
}, 300));

const hasPriority = (playbackPriorityType) => {
  if (playbackPriorityType) {
    return true
  }
}

const openModal = (channel, type) => {
  // const currentItem = {
  //   // id: channel[`${type}_id`], // Dynamically access the ID based on type
  //   type: type, // This could be additional context if needed
  //   // Include other relevant details or fetch based on this ID
  // };
  adminStore.reset();
  adminStore.setSelectedChannel(channel);
  adminStore.setCurrentType(type);
  adminStore.fetchItems(type); // Optionally prefetch items if the modal needs it

  document.getElementById('dynamicModal').showModal(); // Assuming the modal has an ID of 'dynamicModal'
};

</script>

<style scoped>

#tooltip {
  background: #333;
  color: white;
  font-weight: bold;
  padding: 4px 8px;
  font-size: 13px;
  border-radius: 4px;
}

:deep(.popper) {
  background: #333333;
  padding: 2px;
  border-radius: 20px;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
}

:deep(.popper #arrow::before) {
  background: #333333;
}

:deep(.popper:hover),
:deep(.popper:hover > #arrow::before) {
  background: #333333;
}
</style>
