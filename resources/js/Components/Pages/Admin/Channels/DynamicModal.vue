<template>
  <dialog id="dynamicModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl bg-white text-black">

      <div class="flex flex-row justify-between align-bottom">
        <div class="flex gap-2">
          <h3 class="font-bold text-lg">{{ adminStore.modalHeader }}</h3>
          <span v-if="adminStore.loading" class="loading loading-spinner" />
        </div>
        <div>
          <h3>{{ adminStore?.selectedChannel?.name }}</h3>
        </div>
      </div>
      <div class="flex justify-center mb-6">
        <div class="flex items-center mt-6 xl:mt-0">
          <div class="relative">
            <input v-model="adminStore.searchTerm" @input="debouncedFetchItems" type="search" class="bg-gray-50 text-black text-sm rounded-full
                        focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
            <div class="absolute top-0 flex items-center h-full ml-2">
              <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="overflow-x-auto">
            <table class="table table-xs">
            <thead>
            <tr>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Active</span>
                <span v-if="adminStore.currentType === 'mistStream'">Active</span>
              </th>
              <th v-if="adminStore.currentType === 'channelPlaylist'">
                <span>ID</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Name</span>
                <span v-if="adminStore.currentType === 'mistStream'">Name</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Type</span>
                <span v-if="adminStore.currentType === 'mistStream'">Source</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Priority</span>
                <span v-if="adminStore.currentType === 'mistStream'">MIME Type</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Repeat Mode</span>
                <span v-if="adminStore.currentType === 'mistStream'">Comment</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Start Time</span>
                <span v-if="adminStore.currentType === 'mistStream'">Created On</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">End Time</span>
                <span v-if="adminStore.currentType === 'mistStream'"></span>
              </th>
              <th>
                <!-- For delete -->
              </th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="item in adminStore.paginatedItems" :key="item.id" class="hover:bg-blue-300 hover:cursor-pointer"
                :class="{'bg-yellow-500': item.id === adminStore.activeItemId}">
              <td @click="selectItem(item)">
                <div v-if="adminStore.currentType === 'channelPlaylist'">
                  <span :class="getStatusClass(item.status)">
                    {{ item.status }}
                  </span>
                </div>
                <span v-if="adminStore.currentType === 'mistStream'"><span v-if="item.active" class="text-green-500">Active</span><span
                    v-else class="text-red-700">Inactive</span></span>
              </td>
              <td v-if="adminStore.currentType === 'channelPlaylist'">
                <span>{{ item.id }}</span>
              </td>
              <td @click="selectItem(item)">
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.name }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.name }}</span>
              </td>
              <td @click="selectItem(item)">
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.type }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.source }}</span>
              </td>
              <td @click="selectItem(item)">
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.priority }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.mime_type }}</span>
              </td>
              <td @click="selectItem(item)">
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.repeat_mode }}</span>
                <span v-if="adminStore.currentType === 'channelPlaylist' && item.repeat_mode === 'next_playlist'"><br/>{{
                    item?.next_playlist_name
                  }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.comment }}</span>
              </td>
              <td @click="selectItem(item)">
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{
                    dayjs.utc(item.start_dateTime).tz(userStore.timezone).format('MMM D, YYYY, hh:mm A')
                  }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ dayjs.utc(item.created_at).tz(userStore.timezone).format('MMM D, YYYY, hh:mm A') }}</span>

              </td>
              <td @click="selectItem(item)">
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ dayjs.utc(item.end_dateTime).tz(userStore.timezone).format('MMM D, YYYY, hh:mm A') }}</span>
                <span v-if="adminStore.currentType === 'mistStream'"></span>
              </td>
              <td class="w-fit whitespace-nowrap overflow-hidden">
                <button
                    v-if="adminStore.currentType === 'mistStream'"
                    @click.prevent="openEditModal(item)"
                    class="ml-2 px-2 py-1 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
                >
                  <font-awesome-icon icon="fa-pencil" class="text-xs"/>
                </button>
                <button
                    v-if="adminStore.currentType === 'mistStream'"
                    @click="removeItem(item)"
                    class="ml-1 px-2 py-1 text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg"
                >
                  <font-awesome-icon icon="fa-trash-can" class="text-xs"/>
                </button>
                <button
                    v-if="adminStore.currentType === 'channelPlaylist'"
                    @click.prevent="openEditPlaylistModal(item)"
                    class="ml-2 px-2 py-1 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
                >
                  <font-awesome-icon icon="fa-pencil" class="text-xs"/>
                </button>
                <button
                    v-if="adminStore.currentType === 'channelPlaylist' && item.status === 'Inactive'"
                    @click="openConfirmRemoveChannelPlaylistModal(item)"
                    class="ml-1 px-2 py-1 text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg"
                >
                  <font-awesome-icon icon="fa-trash-can" class="text-xs"/>
                </button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <div v-if="adminStore.totalModalPages > 1" class="flex flex-row justify-center mt-6 w-full">
          <div class="join grid grid-cols-2">
            <button
                class="join-item btn btn-sm btn-outline"
                @click="adminStore.prevPage"
                :disabled="adminStore.currentPage === 1"
            >
              Previous page
            </button>
            <button
                class="join-item btn btn-sm btn-outline"
                @click="adminStore.nextPage"
                :disabled="adminStore.currentPage === adminStore.totalModalPages"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <AddOrUpdateMistStreamModal :id="`updateMistStreamModal`" :form-errors="$page.props.errors"
                                  @update-list="refreshList">
        <template #form-title>
          Update Mist Stream
        </template>
        <template #form-description>
          Note: You cannot edit the stream name (right now), it will only create a new stream. This is a bug that needs
          to be fixed in the AddOrUpdateMistStreamJob. To fix this we need to get the list of streams... look for this particular stream and then prepare the streamDetails with the optional parameters that are shown in the stream call. I think that's why it's not updating... because the update may not have all the parameters.
        </template>
        <template #button-label>
          Update
        </template>
      </AddOrUpdateMistStreamModal>

      <dialog id="confirmSelectionModal" class="modal">
        <div class="modal-box bg-white text-black">
          <h3 class="font-bold text-lg">Confirm Selection</h3>
          <p class="py-4">
            Are you sure you want to select <span id="selectedItemName"></span>?
          </p>
          <div class="modal-action">
            <button class="btn" @click="confirmSelection" :disabled="adminStore.loading">Confirm</button>
            <button class="btn" @click="closeConfirmSelectionModal" :disabled="adminStore.loading">Cancel</button>
          </div>
        </div>
      </dialog>

      <dialog id="confirmRemoveModal" class="modal">
        <div class="modal-box bg-white text-black">
          <h3 class="font-bold text-lg">Confirm Remove</h3>
          <p class="py-4">
            Are you sure you want to remove <span id="selectedForRemovalItemName"></span>?
          </p>
          <div class="modal-action">
            <button class="btn" @click="confirmRemove" :disabled="adminStore.loading">Confirm</button>
            <button class="btn" @click="closeConfirmRemoveModal" :disabled="adminStore.loading">Cancel</button>
          </div>
        </div>
      </dialog>

      <dialog id="confirmRemoveChannelPlaylistModal" class="modal">
        <div class="modal-box bg-white text-black">
          <h3 class="font-bold text-lg">Confirm Remove</h3>
          <p class="py-4">
            Are you sure you want to remove <span id="selectedForRemovalItemName"></span>?
          </p>
          <div class="modal-action">
            <button class="btn" @click="removeChannelPlaylist" :disabled="adminStore.loading">Confirm</button>
            <button class="btn" @click="closeConfirmChannelPlaylistRemoveModal" :disabled="adminStore.loading">Cancel</button>
          </div>
        </div>
      </dialog>

      <!-- Edit Playlist Modal -->
      <dialog id="updateChannelPlaylistModal" class="modal">
        <div class="modal-box p-0 flex flex-col h-full">

          <!-- Sticky header -->
          <div class="modal-header sticky top-0 left-0 right-0 bg-white p-4 z-40 border-b border-gray-200">
            <!-- Close button at the top right corner -->
            <form method="dialog" class="absolute right-2 top-2 z-50">
              <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
            <h2 class="text-2xl font-bold">Edit Playlist</h2>
          </div>

          <!-- Modal content with padding to account for the sticky header and footer -->
          <div class="modal-body flex-1 overflow-y-auto">
            <EditPlaylist v-if="selectedPlaylist" :playlist="selectedPlaylist" @update-list="refreshList" />
          </div>

          <!-- Sticky footer -->
          <div class="modal-action sticky bottom-0 left-0 right-0 bg-white p-4 z-40 border-t border-gray-200">
            <button class="btn" @click="closeEditPlaylistModal">Close</button>
          </div>
        </div>
      </dialog>


      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button, it will close the modal -->
          <button class="btn" @click="adminStore.clearSelectedChannelAndItems">Close</button>
        </form>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref, watch, computed, onMounted, defineAsyncComponent } from 'vue'
import { useAdminStore } from '@/Stores/AdminStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChannelPlaylistStore } from '@/Stores/ChannelPlaylistStore'
import debounce from 'lodash/debounce'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { router } from '@inertiajs/vue3'
// Lazy load the components
const AddOrUpdateMistStreamModal = defineAsyncComponent(() => import('@/Components/Global/MistStreams/AddOrUpdateMistStreamModal.vue'))
const EditPlaylist = defineAsyncComponent(() => import('@/Components/Pages/Admin/ChannelPlaylists/EditPlaylist.vue'))

import dayjs from 'dayjs'

const adminStore = useAdminStore()
const channelStore = useChannelStore()
const userStore = useUserStore()
const channelPlaylistStore = useChannelPlaylistStore()
// const searchTerm = ref('');

let props = defineProps({
  type: String,
})

const items = computed(() => adminStore.filteredItems)
const currentItemId = computed(() => adminStore.activeItem)
const selectedPlaylist = ref(null) // Reactive property for the selected playlist

// Create a reactive reference to force updates
const forceUpdate = ref(0);

// const searchItems = () => {
//   adminStore.searchItems(adminStore.type, searchTerm.value);
// };
//
// const debouncedFetchItems = debounce(adminStore.searchItems, 300);

// watch(searchTerm, debouncedFetchItems);


const selectedItem = ref(null) // A reactive property to hold the currently selected item

const selectItem = (item) => {
  selectedItem.value = item // Store the item temporarily
  document.getElementById('selectedItemName').textContent = item.name // Display the name in the modal
  document.getElementById('confirmSelectionModal').showModal() // Open the modal for confirmation
}

const removeItem = (item) => {
  selectedItem.value = item // Store the item temporarily
  document.getElementById('selectedForRemovalItemName').textContent = item.name // Display the name in the modal
  document.getElementById('confirmRemoveModal').showModal() // Open the modal for confirmation
}

const editItem = (item) => {
  selectedItem.value = item // Store the item temporarily
  // Create Modal for editing
  // document.getElementById('selectedForRemovalItemName').textContent = item.name; // Display the name in the modal
  // document.getElementById('confirmRemoveModal').showModal(); // Open the modal for confirmation
}

const confirmSelection = async () => {
  if (selectedItem.value) {
    await adminStore.updateActiveItemId(selectedItem.value.id)
    document.getElementById('confirmSelectionModal').close() // Close the modal
    // Handle additional logic for item selection if necessary
  }
}

const confirmRemove = async () => {
  if (selectedItem.value) {
    removeMistStream(selectedItem.value.name)
    adminStore.removeItem(selectedItem.value)
    document.getElementById('confirmRemoveModal').close() // Close the modal
    // Handle additional logic for item selection if necessary
  }
}
// const selectItem = async (item) => {
//   await adminStore.updateActiveItemId(item.id);
//   // Handle additional logic for item selection if necessary
// };

const openEditModal = (item) => {
  document.getElementById('updateMistStreamModal').showModal()
  channelStore.mistStream = item
}

const removeMistStream = (name) => {
  router.post(route('mistStream.remove'), {'name': name})
  // document.getElementById('dynamicModal').close()
}

const openEditPlaylistModal = (item) => {
  console.log('Edit item opening: ', item)
  selectedPlaylist.value = item
  channelPlaylistStore.setPlaylistData(item)
  document.getElementById('updateChannelPlaylistModal').showModal()
}

const closeEditPlaylistModal = () => {
  channelPlaylistStore.reset()
  document.getElementById('updateChannelPlaylistModal').close()
}

const channelPlaylistRemoveItemHolder = ref()

const openConfirmRemoveChannelPlaylistModal = (item) => {
  channelPlaylistRemoveItemHolder.value = item
  document.getElementById('confirmRemoveChannelPlaylistModal').showModal()
}

const removeChannelPlaylist = () => {
  channelPlaylistStore.removePlaylist(channelPlaylistRemoveItemHolder.value.id)
  adminStore.removeItem(channelPlaylistRemoveItemHolder)
  document.getElementById('confirmRemoveChannelPlaylistModal').close()
}

const closeConfirmChannelPlaylistRemoveModal = () => {
  document.getElementById('confirmRemoveChannelPlaylistModal').close()
}

const refreshList = () => {
  adminStore.fetchItems(props.type)
}

const closeConfirmRemoveModal = () => {
  document.getElementById('confirmRemoveModal').close()
}

const closeConfirmSelectionModal = () => {
  document.getElementById('confirmSelectionModal').close()
}

// onMounted(() => {
//   adminStore.fetchItems(props.type).then(() => {
//     adminStore.setActiveItem(props.type);
//   }).catch(error => {
//     console.error('Error fetching items:', error);
//   });
// });

// Watch the items array in the store
watch(() => adminStore.items, () => {
  forceUpdate.value++; // Increment to force a re-render
});

const getStatusClass = (status) => {
  switch (status) {
    case 'Active':
      return 'text-green-500'
    case 'Inactive':
      return 'text-gray-700'
    case 'Scheduled':
      return 'text-purple-700'
    case 'Standby':
      return 'text-orange-700'
    default:
      return ''
  }
}
// adminStore.fetchItems(props.type)

// onMounted(adminStore.fetchItems(adminStore.type)); // Optionally fetch items when the modal mounts

// const fetchItems = () => {
//   router.get(`/admin/channels/search/${props.type}`, { search: searchTerm.value }, {
//     preserveState: false,
//     onSuccess: (page) => {
//       items.value = page.props.items; // Assume your backend sends back 'items'
//     }
//   });
// };

// watch(searchTerm, fetchItems);

// onMounted(fetchItems); // Optionally fetch items when the modal mounts
</script>


<!--<script setup>-->
<!--import { ref, defineProps, defineEmits } from 'vue';-->

<!--const props = defineProps({-->
<!--  showModal: Boolean,-->
<!--  currentItem: Object,-->
<!--  itemsType: String-->
<!--});-->

<!--const emits = defineEmits(['update:item', 'close']);-->

<!--const searchTerm = ref('');-->
<!--const items = ref([]);-->

<!--const fetchItems = async () => {-->
<!--  // Fetch items based on `itemsType` and `searchTerm`-->
<!--  // Update `items` with the fetched data-->
<!--};-->

<!--const selectItem = (item) => {-->
<!--  emits('update:item', { type: props.itemsType, item });-->
<!--};-->

<!--const closeModal = () => {-->
<!--  emits('close');-->
<!--};-->
<!--</script>-->
