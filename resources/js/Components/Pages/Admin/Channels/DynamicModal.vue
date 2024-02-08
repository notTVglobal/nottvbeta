<template>
  <dialog id="dynamicModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">

      <div class="flex flex-row justify-between mb-6">
        <div>
          <h3 class="font-bold text-lg">{{ adminStore.modalHeader }}</h3>
        </div>
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
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Name</span>
                <span v-if="adminStore.currentType === 'mistStream'">Source</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Type</span>
                <span v-if="adminStore.currentType === 'mistStream'">Name</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Priority</span>
                <span v-if="adminStore.currentType === 'mistStream'">Comment</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Repeat Mode</span>
                <span v-if="adminStore.currentType === 'mistStream'">Created At</span>
              </th>
              <th>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Start Time</span>
                <span v-if="adminStore.currentType === 'mistStream'">Updated At</span>
              </th>
              <th v-if="adminStore.currentType === 'channelPlaylist'">
                <span >End Time</span>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in items" :key="item.id" @click="selectItem(item)">
              <td>
                <div v-if="adminStore.currentType === 'channelPlaylist'"><span v-if="item.active" class="text-green-500">Active</span><span v-else class="text-red-700">Inactive</span></div>
                <div v-if="adminStore.currentType === 'mistStream'"><span v-if="item.active" class="text-green-500">Active</span><span v-else class="text-red-700">Inactive</span></div>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.name }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.source }}</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.type }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.name }}</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.priority }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ item.comment }}</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ item.repeat_mode }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ formatDate(item.created_at) }}</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">{{ formatDateTime(item.start_time) }}</span>
                <span v-if="adminStore.currentType === 'mistStream'">{{ formatDate(item.updated_at) }}</span>
              </td>
              <td v-if="adminStore.currentType === 'channelPlaylist'">
                <span >{{ formatDateTime(item.end_time) }}</span>
              </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Active</span>
                <span v-if="adminStore.currentType === 'mistStream'">Active</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Name</span>
                <span v-if="adminStore.currentType === 'mistStream'">Source</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Type</span>
                <span v-if="adminStore.currentType === 'mistStream'">Name</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Priority</span>
                <span v-if="adminStore.currentType === 'mistStream'">Comment</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Repeat Mode</span>
                <span v-if="adminStore.currentType === 'mistStream'">Created At</span>
              </td>
              <td>
                <span v-if="adminStore.currentType === 'channelPlaylist'">Start Time</span>
                <span v-if="adminStore.currentType === 'mistStream'">Updated At</span>
              </td>
              <td v-if="adminStore.currentType === 'channelPlaylist'">
                <span >End Time</span>
              </td>
            </tr>
            </tfoot>
          </table>
        </div>
        <div class="flex flex-row justify-center mt-6 w-full">
          <div class="join grid grid-cols-2">
            <button class="join-item btn btn-sm btn-outline">Previous page</button>
            <button class="join-item btn btn-sm btn-outline">Next</button>
          </div>
        </div>
      </div>








      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button, it will close the modal -->
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useAdminStore } from '@/Stores/AdminStore'
import debounce from "lodash/debounce"

const adminStore = useAdminStore();
const searchTerm = ref('');

let props = defineProps({
  type: String
})

const items = computed(() => adminStore.filteredItems);
const currentItemId = computed(() => adminStore.activeItem);

// const searchItems = () => {
//   adminStore.searchItems(adminStore.type, searchTerm.value);
// };
//
// const debouncedFetchItems = debounce(adminStore.searchItems, 300);

// watch(searchTerm, debouncedFetchItems);

const selectItem = (item) => {
  adminStore.updateActiveItemId(item.id);
  // Handle additional logic for item selection if necessary
};

onMounted(async () => {
  await adminStore.fetchItems(props.type)
})
// adminStore.fetchItems(props.type)

// onMounted(adminStore.fetchItems(adminStore.type)); // Optionally fetch items when the modal mounts

// const fetchItems = () => {
//   Inertia.get(`/admin/channels/search/${props.type}`, { search: searchTerm.value }, {
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
